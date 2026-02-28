import argparse
import copy
import importlib
import json
import os
import random
import sys
import re
import time
import urllib.parse as urlparse
import glob
import shutil

from queue import PriorityQueue
from urllib.parse import urlencode
from itertools import product
from functools import reduce
from collections import ChainMap, Counter

import traceback
import requests
import utils
from candidate import Candidate
from mutator import DefaultMutator, EmptyQueueMutator, SingleMutator
from scoring import DefaultScoringFormula, FunctionTracesBasedScoring
from vulncheck import DefaultVulnChecker, ParamBasedVulnChecker, ZimpafVulnChecker
from utils import fuzz_open
import hashlib

from libzimpaf import (function_traces, param_traces)
from libzimpaf.constants import (CoverageReportStatus, SoftwareFlaw, Key, CandidateStatus,VulnFuncStatus, 
                                 KeyPayload, APICallTraceStatus, ZendExecStatus,MutationType, 
                                 Vulnerability, NumIterationsThreshold, VulnCheckType, LogicVulnType,
                                 FuzzParamsIterations, FATAL_ERROR_FLAGS, AllFlags, SQLQueryType, HttpMethod)


#def print(*args, **kwargs):
#    pass

class Fuzzer:
    def __init__(self, fuzzer_id):

        self.fuzzer_id = fuzzer_id
        self.start_time = int(time.time())

        self.config = None              #the json file in ../configs used for input mutation
        self.path_hashes = set()        #set of hashes of the paths already found, seems to ease paths cmp

        self.request_timeout = 300        #timeout for requests = 5 seconds
        self.vulnerable_candidates = {}
        self.unique_vulnerable_candidates = {}
        self.exceptions_and_errors_candidates = []
        self.seen_mutations = set()

        self.session = requests.Session()   #never used, why?
        self.login_script = None            #never used, why?  

        self.http_methods = []
        
        self.fixed_headers = {}     #headers, cookies, query_params, body_params are fuzz points for input mutations
        self.fuzz_headers = {}
        self.weight_headers = 0.25
        
        self.fixed_cookies = {}
        self.fuzz_cookies = {}
        self.weight_cookies = 0.25

        self.fixed_query_params = {}
        self.fuzz_query_params = {}
        self.weight_query_params = 0.25

        self.fixed_body_params = {}
        self.fuzz_body_params = {}
        self.weight_body_params = 0.25
                                            #to enable 
        self.fixed_files_params = {}        #added by tennov
        self.fuzz_files_params = {}         #added by tennov
        self.weight_files_params = 0.25     #added by tennot

        self.coverage_files_folder = os.path.join(
            "/shared-tmpfs/", "coverage-reports")
        self.error_files_folder = os.path.join(
            "/shared-tmpfs/", "error-reports")
        self.exception_files_folder = os.path.join(
            "/shared-tmpfs/", "exception-reports"
        )
        self.mysql_errors_folder = os.path.join(
            "/shared-tmpfs/", "mysql-error-reports")
        self.shell_errors_folder = os.path.join(
            "/shared-tmpfs/", "shell-error-reports")
        self.unserialize_errors_folder = os.path.join(
            "/shared-tmpfs/", "unserialize-error-reports")
        self.pathtraversal_errors_folder = os.path.join(
            "/shared-tmpfs/", "pathtraversal-error-reports")
        self.xxe_errors_folder = os.path.join(
            "/shared-tmpfs/", "xxe-error-reports")
        SCRIPT_DIR = os.path.dirname(os.path.abspath(__file__))
        self.output_dir = os.path.join(SCRIPT_DIR, f"output/fuzzer-{fuzzer_id}")
        # if os.path.exists(self.output_dir): #modified by tennov
        #     shutil.rmtree(self.output_dir)
        if not os.path.exists(self.output_dir):
            os.makedirs(self.output_dir)
            

        ### 
        # BEGIN Define Fuzzing modules
        ####
        # self.scoring_formula = DefaultScoringFormula()      #

        #the scoring formula is written by tennov to use Zimpaf function traces based scoring formula
        self.scoring_formula = FunctionTracesBasedScoring()  
        self.mutator = DefaultMutator()
        #self.vulnchecker = DefaultVulnChecker(
        # self.vulnchecker = ParamBasedVulnChecker(
        #     mysql_errors_folder=self.mysql_errors_folder,
        #     shell_errors_folder=self.shell_errors_folder,
        #     unserialize_errors_folder=self.unserialize_errors_folder,
        #     pathtraversal_errors_folder=self.pathtraversal_errors_folder,
        #     xxe_errors_folder=self.xxe_errors_folder,
        #     )

        #added by tennov to use ZimpafVulnChecker
        self.vulnchecker = ZimpafVulnChecker(mysql_errors_folder=self.mysql_errors_folder,
                                                shell_errors_folder=self.shell_errors_folder,
                                                error_files_folder=self.error_files_folder,
                                                exception_files_folder=self.exception_files_folder)
        ### 
        # END Define Fuzzing modules
        ####
        os.umask(0)

    def _open(self, filepath):
        return os.open(filepath, os.O_CREAT | os.O_WRONLY | os.O_TRUNC, 0o777)

    def save_output_vulnerable(self):
        with open(
            self._open(
                os.path.join(
                    self.output_dir,
                    f"vulnerable-candidates.json",
                )
            ),
            "w",
        ) as f:
            json.dump(self.vulnerable_candidates, f, default=lambda x:x.__dict__(), indent=2)

        pathmap = {
            'SQLi': self.mysql_errors_folder,
            'CommandInjection': self.shell_errors_folder,
            'Unserialize': self.unserialize_errors_folder,
            'PathTraversal': self.pathtraversal_errors_folder,
            'XXE': self.xxe_errors_folder
        }

        for k in self.vulnerable_candidates:
            if not k in pathmap:
                continue
            for candidate in self.vulnerable_candidates[k]:
                vuln_info_file = os.path.join(pathmap[k], f"{candidate.coverage_id}.json")
                if not os.path.exists(vuln_info_file):
                    continue
                shutil.copyfile(vuln_info_file, os.path.join(self.output_dir, f"{k}-{candidate.coverage_id}.json"))

        print("Vulnerable candidates saved!")

    def save_output_exceptions_errors(self):
        with open(
            self._open(
                os.path.join(
                    self.output_dir,
                    f"exceptions-and-errors.json",
                )
            ),
            "w",
        ) as f:
            json.dump(self.exceptions_and_errors_candidates, f, default=lambda x: x.__dict__(), indent=2)

        for candidate in self.exceptions_and_errors_candidates:
            exception_path = os.path.join(self.exception_files_folder, f"{candidate.coverage_id}.json")
            error_path = os.path.join(self.error_files_folder, f"{candidate.coverage_id}.json")

            if os.path.exists(exception_path):
                shutil.copyfile(exception_path, os.path.join(self.output_dir, f"exception-{candidate.coverage_id}.json"))
            
            if os.path.exists(error_path):
                shutil.copyfile(error_path, os.path.join(self.output_dir, f"error-{candidate.coverage_id}.json"))

        print("Exceptions and errors candidates saved!")

    def load_request_data(self): #to load fuzzing parameters from json config file into class variables
        potential = {}  #this is a placeholder for json config file to be sent to server. every key can contains multiple values
        potential['methods'] = []   #potentials is used temporary, to move config data to class variables.
        potential['headers'] = []   #since all input params spec are in class variable, multithreading should not be used, but multiprocessing can
        potential['cookies'] = []
        potential['query_params'] = []
        potential['body_params'] = []
        potential['files_params'] = [] #added by tennov

        if 'request_timeout' in self.config:
            self.request_timeout = float(self.config['request_timeout'])

        if 'har_input' in self.config:
            # we only look at the first request in the HAR file
            har_request = utils.extract_input_vectors_from_har(
                f"./resources/har_{self.config['har_input']}.har"
            )[0]

            potential['methods'].append(har_request.get("method", "GET")) #how about post
            potential['headers'] += har_request.get("headers", [])
            potential['cookies'] += har_request.get("cookies", [])
            potential['query_params'] += har_request.get("query_string", [])
            potential['body_params'] += har_request.get("form_data", [])
            # These are {'name': 'value'}-pairs

        if 'methods' in self.config:  #add all methods from config
            potential['methods'] += self.config['methods']

        self.http_methods = list(set(potential['methods'])) #incase several http methods for an endpoint: GET use query_params, POST use body_params
                                                            #set is used to remove duplicates, then put into the list for combination(cart product) with other params
        # Make sure that 'print_timestamps' is set
        self.config['print_timestamps'] = self.config.get('print_timestamps', False)

        if "login" in self.config and self.config["login"]: #login using login script file in config.login
            login_cookies = self.login()
            for k,v in login_cookies.items(): #login is session based,so it will add to fuzz input.Other cookies data, e.g. security=low already in config file
                if 'cookies' in self.config and 'login' in self.config['cookies']:#is there many login creds for a session?
                    for regex in self.config['cookies']['login']:
                        if re.match(regex, k):
                            potential['cookies'].append({'name': k, 'value': v}) #add login from cookies of session that just established
                else:
                    potential['cookies'].append({'name': k, 'value': v}) #else just add all credentials: login and data to potential for input gen

        for config_key in ['headers', 'cookies', 'query_params', 'body_params', 'files_params']: #these are the fuzz points for input mutation
            if not config_key in self.config: #if crawler does not specify these fuzz points, then skip
                continue

            if "data" in self.config[config_key]: #it seems that the 4 fuzz points always have data
                potential[config_key] += self.config[config_key]['data'] #why is this not set directly to class var to be held as fuzz input params?
            else:
                raise Exception(f"Config parsing error: No parameters specified with 'data' for {config_key}")

            if 'weight' in self.config[config_key]: #this is where cookies data are added for candidate gen afterwards
                setattr(self, f"weight_{config_key}", self.config[config_key]['weight']) #weights are set directly to fuzz input params in class var.

        # now filter/assign these to fixed/fuzz params
        for config_key in ['headers', 'cookies', 'query_params', 'body_params', 'files_params']: #this is where fixed params are set. in config there are decsriptors for this in each fuzz point
            # Fixed params need a {'name': name, 'value': value} dict!           #problem: how to separate fixed and fuzz params when both certain regex e.g (Submit) and all (.*) 
            fixed_dict = getattr(self,f"fixed_{config_key}",{})                 #are used in either fixed or fuzz params?
            #print("fixed dict init: ", fixed_dict)
            if config_key in self.config and 'fixed' in self.config[config_key] and self.config[config_key]['fixed']:
                for regex in self.config[config_key]['fixed']:
                    r = re.compile(regex)
                    for param in potential[config_key]:#this is the purpose o potential and why it uses list
                        param_name = param['name']
                        if r.match(param_name):
                            if not param_name in fixed_dict:
                                fixed_dict[param_name] = set()
                            if 'value' in param:
                                fixed_dict[param_name].add(param['value'])
                            elif 'seeds' in param:
                                fixed_dict[param_name].update(param['seeds'])
                            else:
                                raise Exception(f"Neither seeds nor value for param {param_name}")

            fuzz_dict = getattr(self,f"fuzz_{config_key}",{})

            #print("fuzz dict init: ", fuzz_dict)
            if config_key in self.config and 'fuzz' in self.config[config_key] and self.config[config_key]['fuzz']:
                for regex in self.config[config_key]['fuzz']:
                    r = re.compile(regex)
                    for param in potential[config_key]:
                        param_name = param['name']
                        # Ignore fixed params that we have already set.
                        if param_name in fixed_dict: #here, the problem with the use of both certain regex and all regex is solved, since fixed params are set first
                            continue
                        if config_key == 'headers' and param_name.lower() in ["host", "cookie"]:
                            continue
                        if r.match(param_name):
                            if not param_name in fuzz_dict:
                                fuzz_dict[param_name] = set() #make set, to guarantee unique values for fuzz points
                            if 'value' in param:
                                fuzz_dict[param_name].add(param['value'])
                            elif 'seeds' in param:
                                fuzz_dict[param_name].update(param['seeds'])
                            else:
                                raise Exception(f"Neither seeds nor value for param {param_name}")
            else:
                # Fuzz all by default
                for param in potential[config_key]:
                    param_name = param['name']
                    # Ignore fixed params that we have already set.
                    if param_name in fixed_dict:
                        continue
                    if config_key == 'headers' and param_name.lower() in ["host", "cookie"]:
                        continue
                    if not param_name in fuzz_dict:
                        fuzz_dict[param_name] = set() #make set, to guarantee unique values for fuzz points
                    if 'value' in param:
                        fuzz_dict[param_name].add(param['value'])
                    elif 'seeds' in param:
                        fuzz_dict[param_name].update(param['seeds'])
                    else:
                        raise Exception(f"Neither seeds nor value for param {param_name}")


            for k in fixed_dict: #here, set is converted to list, so that it can be used in product() function
                fixed_dict[k] = list(fixed_dict[k])
            for k in fuzz_dict:
                fuzz_dict[k] = list(fuzz_dict[k])
            setattr(self, f"fuzz_{config_key}", fuzz_dict)  #here, fuzz points are set to class variables, so that they can be used in candidate generation
            setattr(self, f"fixed_{config_key}", fixed_dict)#here, fixed points are set to class variables, so that they can be used in candidate generation

    def load_config(self, config_path):
        try:
            self.config = json.load(
                open(os.path.join("./configs", f"{config_path}.json"))
            )
        except Exception as e:
            print(e)
            sys.exit(f"Failed to parse fuzzer config: {config_path}")

        if not self.config["target"].startswith("http"):    #only http(s) scheme is supported
            sys.exit(f"Target does not start with http!")

        if "login" in self.config and not os.path.exists(
            os.path.join("./automated_logins", f"{self.config['login']}.py")
        ):
            sys.exit(f"Login file {self.config['login']} does not exist.")

    def login(self):
        login_script = importlib.import_module(
            f"automated_logins.{self.config['login']}"
        )
        login_script.main()
        print("Ran login script")
        login_cookies = json.load(
            open(
                os.path.join(
                    "/shared-tmpfs", f"cookies_node{os.environ['FUZZER_NODE_ID']}.json"
                ),
                "r",
            )
        )

        print("Found login cookies", login_cookies)
        return login_cookies
    #change the tuple to dict, so that it can be used for request generation
    def _param_tuple_to_dict(self, tpl):
        return dict(ChainMap(*list(map(lambda x: {x['name']: x['value']}, tpl))))

    def generate_initial_candidates(self):

        print("Fixed headers", self.fixed_headers)
        print("Fixed Cookies", self.fixed_cookies)
        print("Fixed Query Params", self.fixed_query_params)
        print("Fixed Body Params", self.fixed_body_params)
        print("Fixed Files Params", self.fixed_files_params)    #added by tennov

        print("Fuzz headers", self.fuzz_headers)
        print("Fuzz cookies", self.fuzz_cookies)
        print("Fuzz query params", self.fuzz_query_params)
        print("Fuzz body params", self.fuzz_body_params)
        print("Fuzz files params", self.fuzz_files_params)      #added by tennov   

        fixed_generators = {}
        fuzz_generators = {}
        #create all combinations of fixed and fuzz params first, then create candidate in the nested loop on line 363
        for keyword in ['headers', 'cookies', 'query_params', 'body_params', 'files_params']:
            fixed_dict = getattr(self, f"fixed_{keyword}", {})
            keyword_comb = []   #to anticipate a web page with select, options, combo, checkbox,multifields and already reflected in config
            for k in fixed_dict:
                tmp_list = []
                for v in fixed_dict[k]:
                    tmp_list.append({'name': k, 'value': v})
                keyword_comb.append(tmp_list)
            
            fixed_generators[keyword] = list(product(*keyword_comb))

            fuzz_dict = getattr(self, f"fuzz_{keyword}", {})
            #print("fuzz dict: ", fuzz_dict)
            keyword_comb = []
            for k in fuzz_dict:
                tmp_list = []
                for v in fuzz_dict[k]:
                    tmp_list.append({'name': k, 'value': v})
                keyword_comb.append(tmp_list)

            fuzz_generators[keyword] = list(product(*keyword_comb))

        for req_method in self.http_methods:
            for fixed_header_comb in fixed_generators['headers']:
                for fixed_cookie_comb in fixed_generators['cookies']:
                    for fixed_query_params_comb in fixed_generators['query_params']:
                        for fixed_body_params_comb in fixed_generators['body_params']:
                            for fixed_files_params_comb in fixed_generators['files_params']:
                                for fuzz_header_comb in fuzz_generators['headers']:
                                    for fuzz_cookie_comb in fuzz_generators['cookies']:
                                        for fuzz_query_params_comb in fuzz_generators['query_params']:
                                            for fuzz_body_params_comb in fuzz_generators['body_params']:
                                                for fuzz_files_params_comb in fuzz_generators['files_params']:

                                                    c = Candidate(
                                                        score=0,        #set by tennov, initially no score   
                                                        priority=0,     #set by tennov, initially priority  
                                                        http_target=self.config['target'],
                                                        http_method=req_method,
                                                        fixed_params={
                                                            'headers': self._param_tuple_to_dict(fixed_header_comb),
                                                            'cookies': self._param_tuple_to_dict(fixed_cookie_comb),
                                                            'query_params': self._param_tuple_to_dict(fixed_query_params_comb),
                                                            'body_params': self._param_tuple_to_dict(fixed_body_params_comb),
                                                            'files_params': self._param_tuple_to_dict(fixed_files_params_comb)  #added by tennov
                                                        },
                                                        fuzz_params={
                                                            'headers': self._param_tuple_to_dict(fuzz_header_comb),
                                                            'cookies': self._param_tuple_to_dict(fuzz_cookie_comb),
                                                            'query_params': self._param_tuple_to_dict(fuzz_query_params_comb),
                                                            'body_params': self._param_tuple_to_dict(fuzz_body_params_comb),
                                                            'files_params': self._param_tuple_to_dict(fuzz_files_params_comb)   #added by tennov
                                                        },
                                                        fuzz_weights={
                                                            'headers': self.weight_headers,
                                                            'cookies': self.weight_cookies,
                                                            'query_params': self.weight_query_params,
                                                            'body_params': self.weight_body_params,
                                                            'files_params': self.weight_files_params
                                                        },
                                                        fuzzer_id=self.fuzzer_id,
                                                        #is_initial_candidate=True
                                                        )
                                                    yield c


    def calculate_score(self, candidate):
        score = self.scoring_formula.calculate_score(candidate)
        candidate.score = score
        return score

    def calculate_priority(self, candidate):
        priority = self.scoring_formula.calculate_priority(candidate)
        candidate.priority = priority
        return priority

    def calculate_energy(self, c):
        return self.scoring_formula.calculate_energy(c)

    def cleanup(self, candidate):
        coverage_file_path = os.path.join(
            self.coverage_files_folder, f"{candidate.coverage_id}.json"
        )

        if os.path.exists(coverage_file_path):
            os.unlink(coverage_file_path)

    def check_for_exception_or_error(self, candidate):
        exception_file = os.path.join(
            self.exception_files_folder, f"{candidate.coverage_id}.json"
        )
        if os.path.exists(exception_file):
            candidate.exceptions = []
            for line in fuzz_open(exception_file, "r"):
                if line.strip():
                    candidate.exceptions.append(json.loads(line))

        error_file = os.path.join(
            self.error_files_folder, f"{candidate.coverage_id}.json"
        )
        if os.path.exists(error_file):
            candidate.errors = []
            for line in fuzz_open(error_file, "r"):
                if line.strip():
                    candidate.errors.append(json.loads(line))


        if candidate.errors or candidate.exceptions:
            print(
                f"\033[91mFound an error or exception with candidate: {candidate.exceptions} // {candidate.errors}\033[0m")
            return True
        else:
            return False

    def prepare_request(self, candidate):
        base_url = candidate.http_target

        # based on https://stackoverflow.com/a/2506477
        url_parts = list(urlparse.urlparse(base_url))
        query = dict(urlparse.parse_qsl(url_parts[4]))
        url_parts[4] = '' # reset query string, which we will set using params={...}

        the_params = {**query, **candidate.fuzz_params['query_params'], **candidate.fixed_params['query_params']}
        the_body_params = {**candidate.fuzz_params['body_params'], **candidate.fixed_params['body_params']}

        #the files_params is added by tennov to allow fuzzing an endpoint whose input contains file to upload
        the_files_params = {**candidate.fuzz_params['files_params'], **candidate.fixed_params['files_params']} #added by tennov
        if the_files_params:
            for key in the_files_params:
                prev_val = the_files_params[key]
                the_files_params[key]=("fuzz.jp", prev_val)
        #stringification of cookies is added by tennov to comply with request lib.
        def _stringify(d):
            return {k: str(v) for k, v in d.items()}
        unstringified_cookies = {**candidate.fuzz_params['cookies'], **candidate.fixed_params['cookies']} # self._urlencode_dict()
        the_cookies = _stringify({**candidate.fuzz_params['cookies'], **candidate.fixed_params['cookies']}) # self._urlencode_dict()
        the_headers = {**candidate.fuzz_params['headers'], **candidate.fixed_params['headers']}
        the_headers["X-FUZZER-COVID"] = candidate.coverage_id

        # print({
        #     'query': the_params,
        #     'cookies': the_cookies,
        #     'headers': the_headers,
        #     'body': the_body_params    
        #     })

        if candidate.http_method in ["GET", "OPTIONS", "TRACE"]:
            req = requests.Request(method=candidate.http_method, 
                                    url=urlparse.urlunparse(url_parts),
                                    params=the_params,
                                    cookies=the_cookies,
                                    headers=the_headers)

        elif candidate.http_method in ["POST", "PUT", "DELETE"]:            
            if the_headers.get('Content-Type', '') in ['application/json']: 
                req = requests.Request(method=candidate.http_method, 
                                    url=urlparse.urlunparse(url_parts),
                                    params=the_params,
                                    cookies=the_cookies,
                                    headers=the_headers,
                                    json=the_body_params)
            elif the_files_params and not the_headers.get('Content-Type', ''): #added by tennov. for post with files
                req = requests.Request(method=candidate.http_method, 
                                    url=urlparse.urlunparse(url_parts),
                                    params=the_params,
                                    cookies=the_cookies,
                                    headers=the_headers,
                                    data=the_body_params,
                                    files=the_files_params)
            else:
                req = requests.Request(method=candidate.http_method, 
                                    url=urlparse.urlunparse(url_parts),
                                    params=the_params,
                                    cookies=the_cookies,
                                    headers=the_headers,
                                    data=the_body_params)

        else:
            raise Exception("Unknown HTTP method!")

        prepared = req.prepare()

        return prepared

    def run(self):
        self.load_request_data()    #if login, do login, set self.<request_timeouts, http_methods, and fixed and fuzz (headers, cookies, params, body_params)
        if self.config['print_timestamps']:
            print(f"START_TIME: {time.time()}")
        self.fuzz_fast()


    #this is where priority used to sort the candidates and chosen as the next candidate. sorted from lowest to highest priority
    def ff_choose_next(self, offset):
        if self.ff_interesting_candidates:#choose interesting candidate first
            #print("interesting: ", [(x.priority, x.score) for x in sorted(self.ff_interesting_candidates)])
            c = sorted(self.ff_interesting_candidates)[-1 -(offset % len(self.ff_interesting_candidates))] #pick the last candidate
        else:                                                                                              #which is the highest priority
            #print("normal: ", [(x.priority, x.score) for x in sorted(self.ff_candidates)])
            c = sorted(self.ff_candidates)[-1 -(offset % len(self.ff_candidates))]#pick the last candidate
        #print("Candidate: ", c)                                                  #which is the highest priority
        #print("We chose: ", (c.priority, c.score), "offset: ", offset)

        return c


    def ff_mutate(self, c):

        mutator = SingleMutator()

        choice_keys = list(filter(lambda x: c.fuzz_params[x], c.fuzz_params))
        choice_weights = list(map(lambda x: c.fuzz_weights[x], choice_keys))
        if not choice_keys or not choice_weights:
            return None
        param_type = random.choices(choice_keys, weights=choice_weights)[0] #mutates one param only.
        param_name = random.choice(list(c.fuzz_params[param_type].keys()))

        param_value = c.fuzz_params[param_type][param_name]
        new_value = mutator.mutate(param_value)

        fuzz_params = copy.deepcopy(c.fuzz_params)  # deep copy to avoid changing the original
        fuzz_params[param_type][param_name] = new_value
        new_candidate = Candidate(
            parent=c,
            priority=self.scoring_formula.calculate_priority(c),
            http_target=c.http_target,
            http_method=c.http_method,
            fixed_params=copy.deepcopy(c.fixed_params),
            fuzz_params=fuzz_params,
            fuzz_weights=copy.deepcopy(c.fuzz_weights),
            fuzzer_id=self.fuzzer_id,
            mutated_param_type=param_type,
            mutated_param_name=param_name
            )

        return new_candidate

    def ff_send_request(self, c):
        try:
            with requests.Session() as s: #create new session for each request, instead of using class var session
                #print(f'Testing candidate: {c.priority} {c.fuzz_params}')  
                prepared_req = self.prepare_request(c)
                response = s.send(prepared_req, timeout=self.request_timeout, allow_redirects=False)
                c.response = response #improvements can be made to dropping the request so that it is not bought up to memory
        except Exception as e:          # from nic.
            print(f"Exception encountered: {e}")
            c.response = None

    def ff_has_vulns(self, c):
        vulns = self.vulnchecker.vuln_check(c)
        if any(vulns):
            for k in vulns:
                if self.config['print_timestamps']:
                    print(f"{k.upper()}_TIME: {time.time()}")
                if not k in self.vulnerable_candidates:
                    self.vulnerable_candidates[k] = []
                    self.unique_vulnerable_candidates[k] = set()
                self.vulnerable_candidates[k].append(c)
                if k not in c.vulns:
                    c.vulns.append(k)

                vuln_id = c.get_paths_hash()
                if not vuln_id in self.unique_vulnerable_candidates[k]:
                    self.unique_vulnerable_candidates[k].add(vuln_id)

                print(
                    f"{k}: {len(self.vulnerable_candidates[k])} ({len(self.unique_vulnerable_candidates[k])})"
                )
            self.save_output_vulnerable()
            print("Found vulns!")
            c.print_candidate_info()
            return True
        else:
            return False

    def ff_is_interesting(self, c):
        if c.number_of_new_paths > 0:
            cph = c.get_paths_hash()
            if cph in self.path_hashes: #if new paths already found, not interesting, else interesting 
                return False
            print(f"\033[92mNew paths found: {c.new_paths}\033[0m\n")
            c.is_interesting = True     #interesting because hash value of candidate.paths not in self.path_hashes
            self.path_hashes.add(cph)
            return True
        return False

    def ff_has_exceptions(self, c):
        error = self.check_for_exception_or_error(c)

        if error:
            self.exceptions_and_errors_candidates.append(c)
            self.save_output_exceptions_errors()
            return True
        else:
            return False
    
    def ff_get_coverage(self, candidate):
        coverage_file_path = (
            f"{self.coverage_files_folder}/{candidate.coverage_id}.json"
        )
        if not os.path.exists(coverage_file_path):
            return 0

        with fuzz_open(coverage_file_path, "r") as f:
            coverage_report = json.load(f)

        if not coverage_report:
            return 0

        hit_paths = utils.extract_hit_paths(coverage_report)
        stringified_hit_paths = set(utils.stringify_hit_paths(hit_paths))

        hit_path_set = set(stringified_hit_paths)

        if candidate.parent:
            parent_paths=set(candidate.parent.paths)
        else:
            parent_paths=set()

        new_paths = hit_path_set.difference(parent_paths) # (self.paths | hit_path_set) - self.paths
        number_of_new_paths = len(new_paths)
        # self.paths.update(hit_path_set)

        candidate.paths = list(stringified_hit_paths | parent_paths)
        candidate.new_paths = new_paths
        candidate.number_of_new_paths = number_of_new_paths
    
    def ff_sync_candidates(self): #seems to load and add sync files that are not in memory/the list of candidates? 
        sync_path = "/sync-tmpfs/"#how can files reside sync_path without ever being loaded, sent as req, and processed?
        #get all file names without extension and path
        file_hashes = set(map(lambda x: x.replace(sync_path,"").replace(".json", ""), glob.glob(sync_path + "[a-z0-9]*.json")))

        new_hashes = file_hashes.difference(self.seen_mutations) 
        #seen_mutations already?                                 
        counter_total = 0
        counter_added = 0
        counter_interesting = 0

        for h in new_hashes:            
            if 'interesting_' in h:
                h = h.replace("interesting_", "")
            #params_hash is used as sync file name and also stored in self.seen_mutations
            c = self.ff_load_candidate(h)   #load sync files that are not in memory (not in self.seen_mutations). How can a file
            chash = c.get_params_hash()     #reside in sync folder, but not present in memory, fuzzing failure?
            if chash not in self.seen_mutations: #why do we need this if just called after candidate is submitted
                self.seen_mutations.add(chash)   #that guarantees that the candidate is not in seen_mutations
                self.ff_candidates.append(c)
                counter_added += 1
            if c.is_interesting and chash not in self.ff_interesting_candidates_hashes:
                self.ff_interesting_candidates_hashes.add(chash)
                self.ff_interesting_candidates.append(c)
                counter_interesting +=1
            counter_total += 1
        return counter_total, counter_added, counter_interesting

    def ff_load_candidate(self, chash):
        c = Candidate()
        c.load_sync_file(candidate_hash=chash)
        self.ff_reset_cookies(c)
        # self.paths.update(c.paths)
        return c

    def ff_reset_cookies(self, candidate):
        if 'cookies' in self.config and 'login' in self.config['cookies']:
            lcrs = self.config['cookies']['login']
        else:
            lcrs = []

        for r in lcrs:
            for k,v in self.fixed_cookies.items():
                if re.match(r, k):
                    candidate.fixed_params['cookies'][k] = v[0] # Always use the first value

    def fuzz_fast(self):
        counter = 0

        self.ff_candidates = [] 
        self.ff_interesting_candidates = []
        self.ff_interesting_candidates_hashes = set()
        self.ff_vulnerable_candidates = []
        #added by tennov
        self.ff_vuln_function_hashes = set ()
        self.ff_vulns = []              #store vulnerabilities found by ZimpafVulnChecker
        self.ff_vulns_hashes = set()    #store unique vulnerabilities found by ZimpafVulnChecker
        payloads_folder = os.path.join(".", 'payloads')
        ff_code_exec_payloads = {}
        ff_path_travs_payloads = {}
        ff_sqli_payloads = {}
        ff_unserialize_payloads = {}
        ff_xxe_payloads = {}
        ff_xss_payloads = {}
        
        #first, restore the fuzzing state if any pending tasks stored to disk.
        self.restore_fuzzing_state()    #data to load: candidate (incomplete) and its hashes, loc: /sync-tmpfs
                                        #              vuln hashes loc: ./output/fuzzer-id and 
        # Send initial requests first
        for c in self.generate_initial_candidates():    #
            self.ff_send_request(c)
            # sys.exit() #uncommented for instrumentation evaluation only
            c.fuzz_status = CandidateStatus.FUZZED
            cov_rep_status = self.zimpaf_get_branch_instr_based_coverage(c)
            if (cov_rep_status == CoverageReportStatus.KNOWN_PATH or cov_rep_status == CoverageReportStatus.NO_PATH):
                del(c)
                continue
            func_traces_path = os.path.join("/shared-tmpfs/function-call-traces",f"{c.coverage_id}.json")
            c.function_trace = utils.load_json_file(func_traces_path)
            function_traces.set_api_call_trace_status(c, self.ff_vuln_function_hashes)
            c.sanitation_report = function_traces.sanitation_report(c=c, trace_is_updated=True)
            c.parameter_comparison_report = param_traces.params_comparisons_report(c=c)
            self.ff_candidates.append(c)        #add new candidate to the list of candidate
            self.save_candidate(c)              #candidate.path_hash is used as file name.
            new_vulns, zend_exec_status = self.zimpaf_has_vulns(c,VulnCheckType.ERR_BASED)  #check for vulnerabilities using ZimpafVulnChecker
            self.scoring_formula.calculate_priority_and_score(c)
            #check if vuln functions execute safely, activate/uncomment this if you want to remove
            #vulnerable functions early.
            # if (c.vuln_functions and not new_vulns and zend_exec_status > ZendExecStatus.DIE_EXIT_TERMINATION):  
            #     function_traces.is_vuln_funcs_executed_in_safe_seq(c)                  
            self.seen_mutations.add(c.get_params_hash())    #add param hash, this is for randomly mutated candidate
            self.save_candidate_param_hash(c) #c.hash contains params_hash and c.path_hash contain candidate path hash

        if self.ff_candidates:
            cur_input = max(self.ff_candidates)
        else:
            print("\033[31mNo candidate to fuzz !!!\033[0m")
            print("Fuzzing Done!!")
            return
        if not cur_input.coverage_id.endswith("_"): #to avoid storing input for every mutation.
            cur_input.coverage_id = f"{cur_input.coverage_id}_" #to enable using only 1 candidate to fuzz one execution path.
        if cur_input.fuzz_status == CandidateStatus.NOT_FUZZED:
            cur_input.fuzz_status = CandidateStatus.FUZZED
        round_time = time.time()
        
        while cur_input: 
            if cur_input.vuln_functions:
                '''
                Firstly short the current input vulnerable function list, based on the number of parameters
                reach every vulnerable function
                '''
                cur_input.vuln_functions.sort(key=lambda x: len(x.get(Key.PARAMS_IN_SINK, {})), reverse=True)

                for func in cur_input.vuln_functions[:]: #shallow copy since if vuln found, func is removed by error,func-base,safeseq check
                    #if vuln func exist, fuzz the function
                    stored_xss_flag = False
                    if (func[Key.VULN_FUNC_STATUS] == VulnFuncStatus.FUZZED or 
                        func[Key.VULN_FUNC_STATUS] == VulnFuncStatus.BUGGY):

                        if func[Key.FUNCTION_NAME] in function_traces.CODE_EXEC_VULN_FUNCTIONS:
                            if not ff_code_exec_payloads:
                                path = os.path.join(payloads_folder, 'code_exec_payloads.json')
                                with open(path, "r") as f:
                                    ff_code_exec_payloads = json.load(f)
                            func_sanit_rep = function_traces.function_sanitation_report(cur_input.sanitation_report, 
                                                                                        func, Key.COMMAND)
                            func[Key.FUNC_SANIT_REP] = func_sanit_rep
                            status = self.generic_fuzz_vuln_function(func,cur_input,ff_code_exec_payloads, 
                                                                     Vulnerability.CODE_EXEC, func_sanit_rep)
                        elif func[Key.FUNCTION_NAME] in function_traces.PATHTRAVS_VULN_FUNCTIONS:
                            if not ff_path_travs_payloads:
                                path = os.path.join(payloads_folder, 'path_travs_payloads.json')
                                with open(path, "r") as f:
                                    ff_path_travs_payloads = json.load(f)
                            func_sanit_rep = function_traces.function_sanitation_report(cur_input.sanitation_report, 
                                                                                        func, Key.PATH) 
                            func[Key.FUNC_SANIT_REP] = func_sanit_rep
                            status = self.generic_fuzz_vuln_function(func,cur_input,ff_path_travs_payloads, 
                                                                     Vulnerability.PATHTRAVS, func_sanit_rep)    
                        elif func[Key.FUNCTION_NAME] in function_traces.SQLI_VULN_FUNCTIONS:
                            stored_xss_flag = function_traces.query_types(func[Key.QUERY])
                            if not ff_sqli_payloads:
                                sql_path = os.path.join(payloads_folder, 'sqli_payloads.json')
                                with open(sql_path, "r") as fs:
                                    ff_sqli_payloads = json.load(fs)
                            func_sanit_rep = function_traces.function_sanitation_report(cur_input.sanitation_report, 
                                                                                        func, Key.QUERY) 
                            func[Key.FUNC_SANIT_REP] = func_sanit_rep
                            status = self.fuzz_sql_function(func, cur_input, ff_sqli_payloads, func_sanit_rep) #
                            # print("SQLi fuzz status: ", status)
                            if stored_xss_flag & (SQLQueryType.INSERT | SQLQueryType.UPDATE):
                                print("Also XSS possible, will fuzz after SQLi done")
                                if not ff_xss_payloads:
                                    xss_path = os.path.join(payloads_folder, 'xss_payloads.json')
                                    with open(xss_path, "r") as fx:
                                        ff_xss_payloads = json.load(fx)
                                status = self.fuzz_stored_xss(c=cur_input,
                                                              payloads_dict=ff_xss_payloads, 
                                                              vuln_func=func, 
                                                              func_class=Key.SQLI_FUNCTION, 
                                                              sanit_rep=func_sanit_rep)
                        elif func[Key.FUNCTION_NAME] in function_traces.UNSERIALIZE_VULN_FUNCTIONS:
                            if not ff_unserialize_payloads:
                                path = os.path.join(payloads_folder, 'unserialize_payloads.json')
                                with open(path, "r") as f:
                                    ff_unserialize_payloads = json.load(f)
                            func_sanit_rep = function_traces.function_sanitation_report(cur_input.sanitation_report, 
                                                                                        func, Key.SERIALIZED_STRING) 
                            func[Key.FUNC_SANIT_REP] = func_sanit_rep
                            status = self.generic_fuzz_vuln_function(func,cur_input,ff_unserialize_payloads, 
                                                                     Vulnerability.UNSERIALIZE, func_sanit_rep)  
                        elif func[Key.FUNCTION_NAME] in function_traces.XXE_VULN_FUNCTIONS:
                            if not ff_xxe_payloads:
                                path = os.path.join(payloads_folder, 'xxe_payloads.json')
                                with open(path, "r") as f:
                                    ff_xxe_payloads = json.load(f)
                            func_sanit_rep = function_traces.function_sanitation_report(cur_input.sanitation_report, 
                                                                                        func, Key.XML_PAYLOAD) 
                            func[Key.FUNC_SANIT_REP] = func_sanit_rep
                            status = self.generic_fuzz_vuln_function(func,cur_input,ff_xxe_payloads, 
                                                                     Vulnerability.XXE, func_sanit_rep)  

            #Fuzz candidate even after fuzzing vuln functions, 
            #to find other vulnerabilites outside vuln functions
            status = self.fuzz_candidate(cur_input)
            if not ff_xss_payloads:
                xss_path = os.path.join(payloads_folder, 'xss_payloads.json')
                with open(xss_path, "r") as fx:
                    ff_xss_payloads = json.load(fx)
            status = self.fuzz_reflected_xss(cur_input, ff_xss_payloads)
            cur_input.fuzz_status = CandidateStatus.DONE

            self.save_candidate(cur_input) #this is to save the candidate whose fuzz_status == CandidateStatus.DONE
            self.ff_candidates.remove(cur_input)
            del(cur_input)
            # marked current input file DONE, becareful with coverage_id modification. since we add one _(uncerscore)
            # for mutation, the saved cur input to be marked done is the one with filename after one _ remove from the
            # last/right position of file name
            # or may be it is best to remove current input
            if self.ff_candidates:
                cur_input = max(self.ff_candidates)
                cur_input.coverage_id = f"{cur_input.coverage_id}_"
                if cur_input.fuzz_status == CandidateStatus.NOT_FUZZED:
                    cur_input.fuzz_status = CandidateStatus.FUZZED
            else:
                break
        print("Fuzzing Done!!")
        return

#These functions below are written by tennov to use zimpaf instrumentation.
#code coverage as a sequence of conditional instructions and the path condition outcome (1 for taken, 0 for not taken)
#used by zimpaf extension

    def zimpaf_get_branch_instr_based_coverage(self, candidate):
        coverage_file_path = (
            f"{self.coverage_files_folder}/{candidate.coverage_id}.json"
        )
        if not os.path.exists(coverage_file_path):
            return CoverageReportStatus.NO_PATH
        try:
            with fuzz_open(coverage_file_path, "r") as f:
                hit_paths = json.load(f)
        except FileNotFoundError:
            return CoverageReportStatus.NO_PATH

        if not hit_paths :
            return CoverageReportStatus.NO_PATH
        
        hit_paths_hash = utils.get_path_hash(hit_paths)
        if hit_paths_hash in self.path_hashes:
            return CoverageReportStatus.KNOWN_PATH
        else:
            self.path_hashes.add(hit_paths_hash)
            hit_paths_list = utils.get_path_list(hit_paths)
            num_branches = utils.get_number_of_branches(hit_paths_list)
            if candidate.parent:
                parent_paths_list=utils.get_path_list(candidate.parent.paths)
            else:
                parent_paths_list=list()
            new_paths_unique_list = utils.get_new_path_list(hit_paths_list, parent_paths_list) # (self.paths | hit_path_set) - self.paths
            num_new_branches = utils.get_number_of_branches(new_paths_unique_list) #file-level, it should be line-levels as well
            if not num_new_branches: #no new paths found, no need to save candidate again
                return CoverageReportStatus.KNOWN_PATH
            
            union_child_parent_paths_unique_list = utils.merge_child_and_parent_paths(hit_paths_list, 
                                                                                      parent_paths_list)
            candidate.paths = utils.stringify_paths_list(union_child_parent_paths_unique_list) #file-level, it should be line-levels as well
            candidate.new_paths = utils.stringify_paths_list(new_paths_unique_list)                                       #file-level, it should be line-levels as well
            candidate.number_of_new_paths = num_new_branches
            
            if not candidate.path_hash:              #new candidate, fresh from fuzz config, never fuzzed
                candidate.path_hash = hit_paths_hash #saving the candidate is done at ff_fuzz, after set trace and apicallstatus, this is also to enable lazy update for next iter
            else:               #if fuzz params only one, need to copy candidate,see in copy_candidate definition
                new_candidate = self.copy_candidate(candidate)  #new candidate because new path is found during fuzzing
                if new_candidate is not None:
                    new_candidate.path_hash = hit_paths_hash
                    self.ff_candidates.append(new_candidate)    #add new candidate to the list of candidate
                    self.save_candidate(new_candidate) 
            
            return CoverageReportStatus.NEW_PATH
        
    def save_candidate(self, c):
        syncpath = "/sync-tmpfs/"
        if c.fuzz_status == CandidateStatus.FUZZED or (
            c.fuzz_status == CandidateStatus.NOT_FUZZED): #candidate.hash as filename to avoid saving candidate which leads to 
            #First we need to serialize c.vuln_functions and c.vuln_functions_done because they have tuple that cannot
            #be written directly to disk.
            #We need to store the original and restore it later after writing. 
            ori_vuln_functions = copy.deepcopy(c.vuln_functions)            #passed by ref in py, refcount incremented
            ori_vuln_functions_done = copy.deepcopy(c.vuln_functions_done)  #passed by ref in py, refcount incremented
            ori_sanitation_report = copy.deepcopy(c.sanitation_report)
            self.copy_and_stringify_tuple_keys(c.vuln_functions)            #passed by ref in py, refcount incremented
            self.copy_and_stringify_tuple_keys(c.vuln_functions_done)       #passed by ref in py, refcount incremented
            c.sanitation_report = self.dcopy_dict_and_stringify_tuple_keys(c.sanitation_report)  
            
            filepath = os.path.join(syncpath, f"{c.path_hash}.json") #the same path with same traces/footprints
            # Open (create if needed) in append mode with permissions 0o777
            fd = os.open(filepath, os.O_CREAT | os.O_WRONLY | os.O_TRUNC, 0o777)
            with os.fdopen(fd, "w") as f:
                f.write(str(c))
                f.flush() 
            
            #restore the original c.vuln_functions and c._vuln_functions_done with tuple keys in 'params_in_sink'
            c.vuln_functions = ori_vuln_functions
            c.vuln_functions_done = ori_vuln_functions_done
            c.sanitation_report = ori_sanitation_report
        elif c.fuzz_status == CandidateStatus.DONE: #if done, only save file as: candidate.hash#4 to indicate fuzzing completed
            filepath = os.path.join(syncpath, f"{c.path_hash}#{c.fuzz_status}") #CandidateStatus.DONE=4 in constants.py
            fd = os.open(filepath, os.O_CREAT | os.O_WRONLY | os.O_TRUNC, 0o777)
            os.close(fd)
    
    def copy_and_stringify_tuple_keys(self, vuln_functions):
        for f in vuln_functions:
            if Key.PARAMS_IN_SINK in f.keys() and f[Key.PARAMS_IN_SINK]:
                f[Key.PARAMS_IN_SINK] = self.dcopy_dict_and_stringify_tuple_keys(f[Key.PARAMS_IN_SINK])

    def dcopy_dict_and_stringify_tuple_keys(self, existing_dict):
        if not existing_dict:
            # return None
            return {}
        copied_dict = {}
        for k, v in existing_dict.items():
            new_k = str(k) if isinstance(k, tuple) else k
            copied_dict[new_k] = v
        return copied_dict
    
    def save_candidate_param_hash(self, candidate): #save all params mutations hash for a candidate identified by
        syncpath = "/sync-tmpfs/"                   #path_hash. 
        filepath = os.path.join(syncpath, f"{candidate.path_hash}-param_hashes")
        fd = os.open(filepath, os.O_CREAT | os.O_WRONLY | os.O_APPEND, 0o777)
        with os.fdopen(fd, "a") as f:
            f.write(str(candidate.hash) + "\n") #candidate hash contains param hash

    def zimpaf_has_vulns(self,  c, check_type, vuln_func=None, param_key_tup=None, 
                                vulnerability= None, logic_vuln_type=None, flags=None):
        if check_type & VulnCheckType.ERR_BASED:                        #error-based vulnerability checker
            vulns, zend_exec_status = self.vulnchecker.vuln_check(c, vuln_func=vuln_func)
        elif check_type & VulnCheckType.FUNC_BASED:                     #function-based vulnerablity checker
            if vulnerability == Vulnerability.CODE_EXEC:
                vulns = self.vulnchecker.generic_function_based_vuln_check(c, vuln_func, param_key_tup, vulnerability)
            elif vulnerability == Vulnerability.PATHTRAVS:
                vulns = self.vulnchecker.generic_function_based_vuln_check(c, vuln_func, param_key_tup, vulnerability)
            elif vulnerability == Vulnerability.SQLI:               
                vulns = self.vulnchecker.sql_function_based_vuln_check(c, vuln_func, param_key_tup)
            elif vulnerability == Vulnerability.UNSERIALIZE:
                vulns = self.vulnchecker.generic_function_based_vuln_check(c, vuln_func, param_key_tup, vulnerability)
            elif vulnerability == Vulnerability.XXE:
                vulns = self.vulnchecker.generic_function_based_vuln_check(c, vuln_func, param_key_tup, vulnerability)
        elif check_type & VulnCheckType.LOGIC_BASED:                    #logic-based vulnerability checker
            vulns = self.vulnchecker.logic_based_vuln_check(c, logic_vuln_type, vuln_func, 
                                                            param_key_tup, flags)
        
        new_vulns = []
        new_hashes = set()
        if any(vulns):
            for v in vulns:
                if v[Key.FLAW_TYPE] == SoftwareFlaw.BUG:
                    k = v[Key.FLAW_TYPE]
                    # print(f"\033[91mFound a BUG: {v} \033[0m")
                else:
                    k = v[Key.VULN_TYPE]
                    # print(f"\033[91mFound a VULNERABILITY: {v} \033[0m")
                # Check if the software flaw is not new or has already been found previously
                v_hash = utils.get_vuln_hash(v, c) 
                
                if not v_hash in self.ff_vulns_hashes:
                    elapsed = time.time() - self.start_time  # elapsed in seconds (float)
                    seconds = int(elapsed) 
                    print(f"\033[91mFOUND a {k} after {seconds} seconds \033[0m")
                    print(f"\033[91mDETAIL: {v} \033[0m")
                    self.ff_vulns_hashes.add(v_hash)        #add to the set of hashes of found vulns
                    self.ff_vulns.append(v)                 #add to the list of found vulns
                    new_vulns.append(v)
                    new_hashes.add(v_hash)
                    v[Key.TIME_TO_DISCOVER] = elapsed
                    c.vulns.append(v)

                    if self.config['print_timestamps']:
                        print(f"{k.upper()}_TIME: {time.time()}")
                        print(f"{k}_DETAILS:\n {v}")  
                else:
                    elapsed = time.time() - self.start_time  # elapsed in seconds (float)
                    seconds = int(elapsed) 
                    print(f"\033[91mFOUND an Old {k} after {seconds} seconds \033[0m")
                    print(f"\033[91mThis has already found before with HASH: {v_hash} \033[0m")
                    print(f"\033[91mDETAIL: {v} \033[0m")
                #vulnerability has been found, fuzzing of vuln func is done, remove from c.vuln_functions
                #and add to c.vuln_functions_done
                if Key.FUNCTION_NAME not in v: #handle non-functional vulns, e.g XSS, OpenRedirect,etc
                    continue
                #if vuln, then remove from candidate list of vulnerable functions
                #removing early, may miss other bugs in the same function, e.g. type viol, domain viol, etc
                for func in c.vuln_functions:   #v is a vuln, not a vuln function, utils.is_function_in_list(...) cannot be used
                    if(v[Key.FUNCTION_NAME] == func[Key.FUNCTION_NAME] and 
                       v[Key.FILENAME]      == func[Key.FILENAME] and 
                       v[Key.LINENO]        == func[Key.LINENO]):
                        if(v[Key.FLAW_TYPE] == SoftwareFlaw.VULNERABILITY):
                            function_traces.update_vuln_func_status_and_remove(func,c,
                                                                               VulnFuncStatus.VULNERABLE)
                        elif v[Key.FLAW_TYPE] == SoftwareFlaw.BUG:
                            func[Key.VULN_FUNC_STATUS] = VulnFuncStatus.BUGGY
                        break
                    
                
        if new_vulns:
            self.zimpaf_save_vulns_hashes_candidate(new_vulns, new_hashes, c)
        if check_type & VulnCheckType.ERR_BASED:
            return new_vulns, zend_exec_status
        elif check_type & VulnCheckType.FUNC_BASED or check_type & VulnCheckType.LOGIC_BASED:
            return new_vulns
    
    def zimpaf_save_vulns_hashes_candidate(self, vulns, hashes, c):
        #separate vulnerabilities and bugs file
        vulns_fname = c.path_hash + "_" + str(c.num_iterations) + "_" + "VULNERABILITIES"  + ".json"
        bugs_fname = c.path_hash + "_"  + str(c.num_iterations) + "_" + "BUGS"  + ".json"
        vulns_info_path = os.path.join(self.output_dir, vulns_fname)
        bugs_info_path = os.path.join(self.output_dir, bugs_fname)

        #separate vulns and bugs
        vulns_list = [v for v in vulns if v.get(Key.FLAW_TYPE) and v.get(Key.FLAW_TYPE) == SoftwareFlaw.VULNERABILITY]
        bugs_list = [v for v in vulns if v.get(Key.FLAW_TYPE) and v.get(Key.FLAW_TYPE) == SoftwareFlaw.BUG]

        request_info = {}
        request_info[Key.HTTP_TARGET] = c.http_target
        request_info[Key.HTTP_METHOD] = c.http_method
        request_info[Key.FIXED_PARAMS] = c.fixed_params 
        request_info[Key.FUZZ_PARAMS] = c.fuzz_params
        if vulns_list:
            copied_vulns = utils.create_json_dumpable_list_dict(vulns)
            vulns_data = {"flaws": copied_vulns, 'request_info': request_info}    
            with open(
                self._open(
                            vulns_info_path
                ),
                "w",
            ) as f:
                json.dump(vulns_data, f, indent=2)
            del(copied_vulns)
            # ANSI escape code for yellow: \033[33m
            # Reset color: \033[0m
            # print(f"\033[33mMORE DETAIL ABOUT VULNERABILITIES at: {vulns_info_path}\033[0m")
            #to make yellow in terminal
            print(f"\033[38;2;255;255;0mMORE DETAIL ABOUT VULNERABILITIES at: {vulns_info_path}\033[0m")

        if bugs_list:
            copied_bugs = utils.create_json_dumpable_list_dict(vulns)
            bugs_data = {"flaws": copied_bugs, 'request_info': request_info}    
            with open(
                self._open(
                            bugs_info_path
                ),
                "w",
            ) as f:
                json.dump(bugs_data, f, indent=2)
            del(copied_bugs)
            # ANSI escape code for yellow: \033[33m
            # Reset color: \033[0m
            print(f"\033[33mMORE DETAIL ABOUT BUGS at: {bugs_info_path}\033[0m")

        hashes_fname = "vuln_hashes"
        vuln_hashes_path = os.path.join(self.output_dir, hashes_fname)
        fd = os.open(vuln_hashes_path, os.O_CREAT | os.O_WRONLY | os.O_APPEND, 0o777)
        with os.fdopen(fd, "a",) as f:
            for h in hashes:
                f.write(str(h) + "\n")
    
    def zimpaf_save_safeseq_confirmation (self, vuln_func, c):
        safeseq_fname = c.path_hash + "_" + str(c.num_iterations) + "_" + "SAFESEQ_CORRECTION"  + ".json"
        safeseq_info_path = os.path.join(self.output_dir, safeseq_fname)

        request_info = {}
        request_info[Key.HTTP_TARGET] = c.http_target
        request_info[Key.HTTP_METHOD] = c.http_method
        request_info[Key.FIXED_PARAMS] = c.fixed_params 
        request_info[Key.FUZZ_PARAMS] = c.fuzz_params
        if vuln_func:
            vf_list = [vuln_func]
            copied_vf_list = utils.create_json_dumpable_list_dict(vf_list)
            safeseq_data = {"Vuln Function executed safely": copied_vf_list, 'request_info': request_info}    
            with open(
                self._open(
                            safeseq_info_path
                ),
                "w",
            ) as f:
                json.dump(safeseq_data, f, indent=2)
            del(copied_vf_list)
            print(f"\033[38;2;255;255;0mCorrection of vuln func, which is actually safe is at: {safeseq_info_path}\033[0m")

    '''            
    Restore_fuzzing_state is done when the previous fuzzing has not 100% completed:
    1st, get all the candidate hashes, represented by path_hash that is used as json filename in /sync-tmpfs
    2nd, load candidate hashes that are not in self.path_hashes. this includes candidate that has status DONE because
      in the future fuzzing, new path that is exactly the same as DONE candidate can be found. no need to fuzz this
    3rd, get the hashes of candidate that is needed to load.
    4th, in loop, load candidate and its parameter hashes.
    '''
    
    def restore_fuzzing_state(self):
        #first, restore candidates with status fuzzed (not done) and their hashes
        self.restore_fuzzed_candidate_and_hashes()
        #second, restore vulnerability hashes
        vuln_hashes_path = f"{self.output_dir}/" + "vuln_hashes"
        try:
            with open(vuln_hashes_path, "r") as f:
                vuln_hashes = [line.strip() for line in f if line.strip()]
                self.ff_vulns_hashes.update(vuln_hashes)
        except:
            print(f"No vuln hashes for Fuzzer-{self.fuzzer_id}: The stacktrace:")
            traceback.print_exc(file=sys.stdout)
            print(f"No vuln hashes for Fuzzer-{self.fuzzer_id}: Continue executing") 

    def restore_fuzzed_candidate_and_hashes(self):
        print(f"Restoring saved candidates")
        sync_path = "/sync-tmpfs/"
        synced_candidate_hashes = set(map(lambda x: x.replace(sync_path,"").replace(".json", ""), #1.load all hashes
                                              glob.glob(sync_path + "[a-z0-9]*.json")))
        if not synced_candidate_hashes:
            return
        chashes_not_in_fuzzer = synced_candidate_hashes.difference(self.path_hashes) #2.filter already in self.path_hashes
        self.path_hashes.update(chashes_not_in_fuzzer)                      #3. add to self.path_hashes,to prevent processing found paths.
        chashes_already_done = set(map(lambda x: x.replace(sync_path,"").replace(f"#{CandidateStatus.DONE}", ""), #4.get completed hashes
                                              glob.glob(sync_path + f"[a-z0-9]*#{CandidateStatus.DONE}")))
        chashes_need_to_load = chashes_not_in_fuzzer.difference(chashes_already_done) #5. filter candidate hash needs to load
        val_tuple_key_attrs = ['vuln_functions', 'vuln_functions_done'] #multi-level dict whose values with tuple keys
        tuple_key_attrs = ['sanitation_report'] #dict whose keys are tuples
        for ch in chashes_need_to_load:
            filename = os.path.join(sync_path,f"{ch}.json")
            c = self.zimpaf_load_candidate(filename, val_tuple_key_attrs, tuple_key_attrs)
            if c:
                self.ff_candidates.append(c)
                param_hashes_path = os.path.join(sync_path,f"{ch}-param_hashes")
                try:
                    with open(param_hashes_path, "r") as f:
                        param_hashes = [line.strip() for line in f if line.strip()]
                        self.seen_mutations.update(param_hashes)
                except:
                    print(f"No param hashes for this candidate: {ch}. The stacktrace:")
                    traceback.print_exc(file=sys.stdout)
                    print(f"No param hashes for this candidate: {ch}. Continue executing")

    def zimpaf_load_candidate (self, filename, val_tuple_key_attrs, tuple_key_attrs):
        #Restore Candidate object from JSON file with path hash as filename.
        print(f"Load candidate:{filename}")
        with open(filename, 'r') as f:
            try:
                data = json.load(f)
            except Exception as e:
                print(f"Candidate: {filename} is corrupted. Error message: {e}")
                return None
        
        for attr in (data or {}).keys():
            if attr in val_tuple_key_attrs:
                funcs_list = data[attr]
                for func in funcs_list:
                    if Key.PARAMS_IN_SINK in func.keys() and func[Key.PARAMS_IN_SINK]:
                        pis_dict = func[Key.PARAMS_IN_SINK]
                        func[Key.PARAMS_IN_SINK] = {tuple(k.strip("()").replace("'", "").split(", ")): v for k, 
                                                                                        v in pis_dict.items()}
            elif attr in tuple_key_attrs:
                # {tuple(k.strip("()").replace("'", "").split(", ")): v for k, v in pis_dict.items()}
                report ={}
                for k, v in data[attr].items():
                    key_tuple = tuple(x.strip("'") for x in k.strip("()").split(", "))
                    report[key_tuple] = v
                data[attr] = report
                      
        c = Candidate.__new__(Candidate)  # create object without calling __init__
        for key, value in data.items():
            setattr(c, key, value)
        c.vulns = []            #since not written to disk, this is not used by redphuzz?
        c.parent = None         #since not written to disk
        c.response = None       #since not written to disk
        c.function_trace = []   #since not written to disk
        c.sanit_functions = []  #since not written to disk
        self.ff_reset_cookies(c)
        return c  
    
    def fuzz_sql_function(self, vuln_func, c, sqli_payloads_dict, sanit_rep=None):
        print(f"Fuzzing SQL function: {vuln_func[Key.FUNCTION_NAME]}")
        print(f"Location: {vuln_func[Key.FILENAME]}:{vuln_func[Key.LINENO]}")
        print(f"====================== SQLi Fuzzing Started ====================")
        
        if not sanit_rep:
            sanit_rep = function_traces.sanitation_report(c, Vulnerability.SQLI,vuln_func)
        if sanit_rep:
            for key in sanit_rep:
                if key not in vuln_func[Key.PARAMS_IN_SINK]:
                    #currently, params in sink indentified by sanitation report, cannot identify 
                    # data type and quoted flag
                    vuln_func[Key.PARAMS_IN_SINK][key] = {Key.DATA_TYPE: None,Key.QUOTED: None}

        if Key.PARAMS_IN_SINK in vuln_func and vuln_func[Key.PARAMS_IN_SINK]:
            print(f"Fuzzing sql vuln function with parameters in sink: {vuln_func[Key.FUNCTION_NAME]}")
            print(f"Location: {vuln_func[Key.FILENAME]}:{vuln_func[Key.LINENO]}")
            params_in_sink = vuln_func[Key.PARAMS_IN_SINK]
            fbc_status = VulnFuncStatus.FUZZED
            for key in vuln_func[Key.PARAMS_IN_SINK]:
                key = (key[0],key[1]) #recheck later, why did i write this, seems useless.
                if Key.QUOTED in params_in_sink[key] and params_in_sink[key][Key.QUOTED]:
                    payloads = (sqli_payloads_dict[KeyPayload.MALFORMED][KeyPayload.QUOTED] + 
                                sqli_payloads_dict[KeyPayload.WELLFORMED][KeyPayload.QUOTED])
                    len_malformed = len(sqli_payloads_dict[KeyPayload.MALFORMED][KeyPayload.QUOTED])
                elif Key.QUOTED in params_in_sink[key] and not params_in_sink[key][Key.QUOTED]:
                    if not sanit_rep:
                        payloads = (sqli_payloads_dict[KeyPayload.MALFORMED][KeyPayload.UNQUOTED] + 
                                    sqli_payloads_dict[KeyPayload.WELLFORMED][KeyPayload.UNQUOTED])
                        len_malformed = len(sqli_payloads_dict[KeyPayload.MALFORMED][KeyPayload.UNQUOTED])
                   
                    # This is for sanitation aware fuzzing, after sanitation report is available
                    # As a temporary solution, the current implementation does not identify the data type and quoted flag.
                    # The program should updated to indentify quoted flag   
                    else:
                        payloads = (sqli_payloads_dict[KeyPayload.MALFORMED][KeyPayload.QUOTED] + 
                                    sqli_payloads_dict[KeyPayload.MALFORMED][KeyPayload.UNQUOTED]+
                                    sqli_payloads_dict[KeyPayload.WELLFORMED][KeyPayload.QUOTED] + 
                                    sqli_payloads_dict[KeyPayload.WELLFORMED][KeyPayload.UNQUOTED])
                        len_malformed = (len(sqli_payloads_dict[KeyPayload.MALFORMED][KeyPayload.QUOTED]) +
                                        len(sqli_payloads_dict[KeyPayload.MALFORMED][KeyPayload.UNQUOTED]))
                    
                len_payloads = len(payloads)
                type_viol_max = len_payloads + FuzzParamsIterations.TYPE_VIOLATION
                domain_viol_max = type_viol_max + FuzzParamsIterations.DOMAIN_VIOLATION
                zero_or_empty_max = domain_viol_max + FuzzParamsIterations.ZERO_OR_EMPTY
                type_conform_max = zero_or_empty_max + FuzzParamsIterations.TYPE_CONFORM
                safeseq_start = type_conform_max
                max_trials = safeseq_start + FuzzParamsIterations.SAFE_SEQ

                c.mutated_param_type = key[0]
                c.mutated_param_name = key[1]

                vuln_func[Key.NUM_PARAMS_ITERATIONS] = 0 #reset number of iterations for this param to 0
                while vuln_func[Key.NUM_PARAMS_ITERATIONS] < max_trials:
                    if payloads:
                        if vuln_func[Key.NUM_PARAMS_ITERATIONS] < len_payloads:
                            self.zimpaf_mutate_sql_function_params(vuln_func, key, MutationType.DICTIONARY, c, payloads, len_payloads)
                        elif vuln_func[Key.NUM_PARAMS_ITERATIONS] < type_viol_max:
                            if Key.DATA_TYPE in params_in_sink[key] and params_in_sink[key][Key.DATA_TYPE]:
                                self.zimpaf_mutate_sql_function_params(vuln_func, key, MutationType.TYPE_VIOLATION, c)
                            else:
                                vuln_func[Key.NUM_PARAMS_ITERATIONS] += FuzzParamsIterations.TYPE_VIOLATION 
                                continue
                        elif vuln_func[Key.NUM_PARAMS_ITERATIONS] < domain_viol_max:
                            self.zimpaf_mutate_sql_function_params(vuln_func, key, MutationType.DOMAIN_VIOLATION, c)
                        elif vuln_func[Key.NUM_PARAMS_ITERATIONS] < zero_or_empty_max:
                            self.zimpaf_mutate_sql_function_params(vuln_func, key, MutationType.ZERO_OR_EMPTY, c) 
                        elif vuln_func[Key.NUM_PARAMS_ITERATIONS] < max_trials:
                            self.zimpaf_mutate_sql_function_params(vuln_func, key, MutationType.TYPE_CONFORM, c) 
                    # else:
                    #      self.zimpaf_mutate_sql_function_params(vuln_func, key, MutationType.RANDOM, c) 
                    if sanit_rep and key in sanit_rep:
                        sanit_seq = sanit_rep[key][0]
                        if sanit_seq:
                            c.fuzz_params[key[0]][key[1]] = function_traces.generate_sanitation_aware_string(sanit_seq,
                                                                                        c.fuzz_params[key[0]][key[1]])
                    mutated_param_hash = c.get_params_hash()
                    if mutated_param_hash in self.seen_mutations:
                        vuln_func[Key.NUM_PARAMS_ITERATIONS] += 1 
                        vuln_func[Key.NUM_FUNC_ITERATIONS] += 1
                        continue
                    else:
                        self.seen_mutations.add(mutated_param_hash)    #add param hash, this is for randomly mutated candidate
                        self.save_candidate_param_hash(c) 
                    
                    cov_rep_status = self.delivery_and_coverage_check(c)                 #send_request, check if new path found, if found, create new candidate
                    '''
                    1.error-based vulnerability check by investigating error files, if any, this is always done regardless of which payload used
                    '''
                    ebc_status = self.error_based_vuln_check(c=c, vuln_func=vuln_func)
                    if ebc_status == VulnFuncStatus.VULNERABLE:
                        print(f"SQLI vulnerability is found via error-based check: {vuln_func[Key.FUNCTION_NAME]}")
                        print("f====================== SQLi Fuzzing Complete ====================")
                        return VulnFuncStatus.VULNERABLE
                    
                    #2.function-based check, vuln func executed well, performed only when dictionary/crafted malicious payload is used                     
                    if vuln_func[Key.NUM_PARAMS_ITERATIONS] < len_payloads:   
                        fbc_status = self.function_based_check(vuln_func=vuln_func,
                                                                key=key,
                                                                c=c,
                                                                vulnerability=Vulnerability.SQLI,
                                                                len_payloads=len_payloads)
                        if fbc_status == VulnFuncStatus.VULNERABLE:
                            ssc_status = self.safe_sequence_check(vuln_func=vuln_func,
                                                            c=c,
                                                            vulnerability=Vulnerability.SQLI)
                            if ssc_status == VulnFuncStatus.IN_SAFESEQ:
                                '''
                                Function-based checker decides vulnerability since malicious payload is sent
                                and the function returns object (not fail). Hoever, the sanitation has tamed
                                the malicious payload. Therefore, the function is safely excuted.
                                '''
                                print(f"SQLi Vulnerable function is actually safely executed: {vuln_func[Key.FUNCTION_NAME]}")
                                print(f"Sanitation has tamed the malicious payload")
                                self.zimpaf_save_safeseq_confirmation (vuln_func, c)
                                print(f"====================== SAFE SEQUENCE CORRECTION DONE ====================")
                                #Next, need to find bugs, append vuln_func back to c.vuln_functions
                                #set, iteration to len_payloads, skipping function_based_check
                                c.vuln_functions.append(vuln_func)
                                vuln_func[Key.NUM_PARAMS_ITERATIONS] = len_payloads
                            else: 
                                print(f"SQLI vulnerability is found via function-based check: {vuln_func[Key.FUNCTION_NAME]}")
                                print("f====================== SQLi Fuzzing Complete ====================")
                                return VulnFuncStatus.VULNERABLE
                        
                    #3.is vuln function executed safely check, only performed after violations payload to uncover bug sent/used. 
                    #  vuln function need to be updated, see function_based_check
                    if vuln_func[Key.NUM_PARAMS_ITERATIONS] >= safeseq_start:
                        ssc_status = self.safe_sequence_check(vuln_func=vuln_func,
                                                            c=c,
                                                            vulnerability=Vulnerability.SQLI)
                        if ssc_status == VulnFuncStatus.IN_SAFESEQ:
                            print(f"SQLi Vulnerable function is safely executed: {vuln_func[Key.FUNCTION_NAME]}")
                            print(f"====================== SQLi Fuzzing Complete ====================")
                            return VulnFuncStatus.IN_SAFESEQ 
                               
                    # update all iterations counter, at parameter, function, and candidate level.
                    vuln_func[Key.NUM_PARAMS_ITERATIONS] += 1   #total number of params iterations for controlling fuzz parameters loop
                    vuln_func[Key.NUM_FUNC_ITERATIONS] += 1     #total number of function iterations in fuzzing
                    # c.num_iterations += 1                       #total number of candidate iterations in fuzzing
            function_traces.update_vuln_func_status_and_remove(vuln_func, c, VulnFuncStatus.UNRESOLVED)
            print(f"====================== SQLi Fuzzing Complete ====================")
            return VulnFuncStatus.UNRESOLVED 
        else:
            print(f"Fuzzing sql vuln function with NO parameters in sink: {vuln_func[Key.FUNCTION_NAME]}")
            print(f"Location: {vuln_func[Key.FILENAME]}:{vuln_func[Key.LINENO]}")
            vf_status = self.fuzz_vuln_func_without_params_in_sink(vuln_func=vuln_func,
                                                                   c=c,
                                                                   payloads_dict=sqli_payloads_dict,
                                                                   vulnerability=Vulnerability.SQLI)
            print("Completed fuzzing sqli vulnerable function.")
            return vf_status
               
                                        # (vuln_func, key, MutationType.DICTIONARY, c, payloads, len_payloads)
    def zimpaf_mutate_sql_function_params(self, func, key, mttn_type, c, payloads=None, len_payloads=0):
        if mttn_type == MutationType.DICTIONARY and payloads is not None:
            c.fuzz_params[key[0]][key[1]] = payloads[func[Key.NUM_PARAMS_ITERATIONS]]
            # print(c.fuzz_params[key[0]][key[1]])
            #if insert query and payloads are still applied, avoid primary key constraint violation, 
            # generate integer string
            if (
                (Key.QUERY in func and utils.is_insert_query(func[Key.QUERY])) 
                or Key.BIND_EXECUTE_FUNC in func
                ):
                for req_part, params in c.fuzz_params.items():
                    for param, _ in params.items():
                        if (req_part, param) != key:
                            c.fuzz_params[req_part][param] = utils.generate_integer_string()
                    
        else:
            for req_part, params in c.fuzz_params.items():
                for param, value in params.items():
                    key = (req_part, param)
                    if key in func[Key.PARAMS_IN_SINK]:
                        data_type = func[Key.PARAMS_IN_SINK][key][Key.DATA_TYPE]
                        if mttn_type == MutationType.TYPE_VIOLATION:
                            c.fuzz_params[req_part][param] = utils.generate_type_violation(data_type)
                        elif mttn_type == MutationType.DOMAIN_VIOLATION:
                            c.fuzz_params[req_part][param] = utils.generate_domain_violation(data_type)
                        elif mttn_type == MutationType.ZERO_OR_EMPTY:
                            c.fuzz_params[req_part][param] = utils.generate_zero_or_empty(data_type)
                        elif mttn_type == MutationType.TYPE_CONFORM:
                            c.fuzz_params[req_part][param] = utils.generate_type_conformant(data_type)
                        elif mttn_type == MutationType.RANDOM:
                            c.fuzz_params[req_part][param] = utils.generate_type_conformant()
        

    def copy_candidate(self, c):
        num_fuzz_params = sum(len(params) for params in c.fuzz_params.values())
        fixed_params = copy.deepcopy(c.fixed_params)
        fuzz_params = copy.deepcopy(c.fuzz_params)
        fuzz_weights = copy.deepcopy(c.fuzz_weights)
        paths = copy.copy(c.paths)

        '''
        If #fuzzed_parameters is more than 1 and there is mutated param, the mutated param is canged to 
        fixed param. Otherwise, no change/freezed is made. 
        '''
        if num_fuzz_params > 1:
            req_part = getattr(c, "mutated_param_type", None)
            prm = getattr(c, "mutated_param_name", None)      
            if req_part and prm:
                fixed_params[req_part][prm] = c.fuzz_params[req_part][prm]
                del fuzz_params[req_part][prm]

            if c.mutated_parameter_comparison:
                for key in c.mutated_parameter_comparison:
                    if key[0] in fuzz_params and key[1] in fuzz_params[key[0]]:
                        fixed_params[key[0]][key[1]] = c.fuzz_params[key[0]][key[1]]
                        del fuzz_params[key[0]][key[1]]
                c.mutated_parameter_comparison.clear()

        new_c = Candidate(
            # coverage_id=copy.deepcopy(c.coverage_id),
            coverage_id=c.coverage_id, #change in every iteration: coverage_id += "_num_iteration"
            parent=c,
            http_target=c.http_target,
            http_method=c.http_method,
            fixed_params=fixed_params,
            fuzz_params=fuzz_params,
            fuzz_weights=fuzz_weights,
            fuzzer_id=self.fuzzer_id
            )
        # new_c.num_iterations +=1  #parent candidate num_iterations incremented
        new_c.parent_cov_id = c.coverage_id 
        new_c.paths = paths
        func_traces_path = os.path.join("/shared-tmpfs/function-call-traces",f"{new_c.coverage_id}.json")
        new_c.function_trace = utils.load_json_file(func_traces_path)
        function_traces.set_api_call_trace_status(new_c,self.ff_vuln_function_hashes)
        new_c.sanitation_report = function_traces.sanitation_report(new_c) #new path may imply new sanitation, 
                                                                #or should we lazily delay this?
        new_c.parameter_comparison_report = param_traces.params_comparisons_report(c)
        return new_c
    
    def fuzz_vuln_func_without_params_in_sink(self, vuln_func, c, payloads_dict, vulnerability):
        print(f"Fuzzing vulnerable function without parameters in sink: {vuln_func[Key.FUNCTION_NAME]}")
        print(f"Location: {vuln_func[Key.FILENAME]}:{vuln_func[Key.LINENO]}")
        print(f"========= {vulnerability} Fuzzing Without Parameters in Sink Fuzzing Started =============")
        bind_execute_flag = 0
        params_in_sink_flag = 0
        '''
        If vuln func is a safely executed prepare function, no params in sink is expected. Then, 
        the fuzzing continues to fuzz the bind or execute function to uncover bugs.
        '''
        if '::prepare' in vuln_func[Key.FUNCTION_NAME] or "_prepare" in vuln_func[Key.FUNCTION_NAME]:
            if c.coverage_id.endswith("_"): #probably restored candidate or c is just initiated and not submitted
                func_traces_path = f"/shared-tmpfs/function-call-traces/{c.coverage_id[:-1]}.json"
            else:
                func_traces_path = os.path.join("/shared-tmpfs/function-call-traces",f"{c.coverage_id}.json")
            c.function_trace = utils.load_json_file(func_traces_path)
            updated_vuln_func = utils.get_updated_function(vuln_func, c.function_trace)
            retval = updated_vuln_func[Key.RETURN_VALUE]
            if retval:
                successors = utils.find_successor_function(retval, Key.OBJECT_POINTER, c.function_trace)
                params_list = []
                for successor in successors:
                    if successor and Key.BIND_EXECUTE_FUNC in successor and successor[Key.BIND_EXECUTE_FUNC]:
                        if successor[Key.FUNCTION_NAME] == 'mysqli_stmt_bind_param':
                            param_with_object = successor[Key.PARAMETERS]
                            real_param = param_with_object.split("_",1)[1]
                            params_list.append(real_param)
                            continue
                        params_list.append(successor[Key.PARAMETERS])
                sink = utils.substitute_placeholders_with_params(updated_vuln_func[Key.QUERY], params_list)
                if sink:   
                    for successor in successors:
                        successor[Key.PARAMS_IN_SINK] = function_traces.get_params_in_sink(c.fuzz_params, sink, successor,
                                                                                           c.http_target)
                        bind_execute_flag = 1
                        params_in_sink_flag = 1
                
        '''
        To check if sanitation/encoding has been applied to parameters that makes 
        function_traces.get_params_in_sink(...) unable to find params in sink, 
        when in fact, parameters reach the sink. Therefore, sanitation must be checked. 
        If sanitation report indicates some parameters reach the sink, those parameters are added
        to vuln_func[Key.PARAMS_IN_SINK] with unknown data type and quoted flag and
        corresponding fuzzing function is called. 
        '''
        sanit_rep = function_traces.sanitation_report(c, vulnerability,vuln_func)
        if sanit_rep:
            for key in sanit_rep.keys():
                vuln_func[Key.PARAMS_IN_SINK][key] = {Key.DATA_TYPE: None,Key.QUOTED: None}
            if vulnerability == Vulnerability.SQLI:
                return self.fuzz_sql_function(vuln_func=vuln_func,
                                                      c=c,
                                                      sqli_payloads_dict=payloads_dict, 
                                                      sanit_rep=sanit_rep)
            else:
                                                    #vuln_func, c, payloads_dict, vulnerability, sanit_rep=None
                return self.generic_fuzz_vuln_function(vuln_func=vuln_func,
                                                       c=c,
                                                       payloads_dict=payloads_dict,
                                                       vulnerability=vulnerability,
                                                       sanit_rep=sanit_rep)
               
        try_hit_sink_max = FuzzParamsIterations.TRY_HITTING_SINK
        numeric_zero_max = try_hit_sink_max + FuzzParamsIterations.NUMERIC_ZERO
        empty_string_max = numeric_zero_max + FuzzParamsIterations.EMPTY_STRING
        numeric_only_max = empty_string_max + FuzzParamsIterations.NUMERIC_ONLY
        string_only_max = numeric_only_max + FuzzParamsIterations.STRING_ONLY
        type_viol_max = string_only_max + FuzzParamsIterations.TYPE_VIOLATION
        domain_viol_max = type_viol_max + FuzzParamsIterations.DOMAIN_VIOLATION
        safeseq_start = domain_viol_max
        max_trials = safeseq_start + FuzzParamsIterations.SAFE_SEQ

        vuln_func[Key.NUM_PARAMS_ITERATIONS] = 0
        '''
        For efficiency, after 3 iterations, the sink is checked for changes, if not it is highly likely that 
        the string is indeed a constant. The checking is done by comparing previous hash with current hash of
        the sink.
        '''
        sink = utils.get_sink(vuln_func, vulnerability)
        prev_sink_hash = hashlib.sha256(str(sink).encode("utf-8")).hexdigest()
        same_hash_counter = 0


        # while True:
        mutation_type = None
        while vuln_func[Key.NUM_PARAMS_ITERATIONS] < max_trials:
            if vuln_func[Key.NUM_PARAMS_ITERATIONS] < try_hit_sink_max:
                mutation_type = MutationType.TRY_HITTING_SINK
                self.mutate_params_with_int_string(c)
            elif vuln_func[Key.NUM_PARAMS_ITERATIONS] < numeric_zero_max:
                mutation_type = MutationType.NUMERIC_ZERO
                self.mutate_params_with_numeric_zero(c)
            elif vuln_func[Key.NUM_PARAMS_ITERATIONS] < empty_string_max:
                mutation_type = MutationType.EMPTY_STRING
                self.mutate_params_with_empty_string(c)
            elif vuln_func[Key.NUM_PARAMS_ITERATIONS] < numeric_only_max:
                mutation_type = MutationType.NUMERIC_ONLY
                self.mutate_params_with_numeric_only(c)
            elif vuln_func[Key.NUM_PARAMS_ITERATIONS] < string_only_max:
                mutation_type = MutationType.STRING_ONLY
                self.mutate_params_with_string_only(c)
            elif vuln_func[Key.NUM_PARAMS_ITERATIONS] < type_viol_max:
                mutation_type = MutationType.TYPE_VIOLATION
                if bind_execute_flag and params_in_sink_flag:
                    for successor in successors:
                        for key in successor.get(Key.PARAMS_IN_SINK, []):
                            self.zimpaf_mutate_sql_function_params(successor,key,mutation_type,c)
                else:
                    self.mutate_params_with_type_violation(c)
            elif vuln_func[Key.NUM_PARAMS_ITERATIONS] < domain_viol_max:
                mutation_type = MutationType.DOMAIN_VIOLATION
                if bind_execute_flag and params_in_sink_flag:
                    for successor in successors:
                        for key in successor.get(Key.PARAMS_IN_SINK, []):
                            self.zimpaf_mutate_sql_function_params(successor,key,mutation_type,c)
                else:
                    self.mutate_params_with_domain_violation(c)
            elif vuln_func[Key.NUM_PARAMS_ITERATIONS] < max_trials:
                self.mutate_params_randomly(c)

            # func_traces_path = os.path.join("/shared-tmpfs/function-call-traces",f"{c.coverage_id}.json")
            # c.function_trace = function_traces.load_func_call_traces(func_traces_path)
            mutated_param_hash = c.get_params_hash()
            if mutated_param_hash in self.seen_mutations:
                vuln_func[Key.NUM_PARAMS_ITERATIONS] += 1
                vuln_func[Key.NUM_FUNC_ITERATIONS] += 1
                continue
            else:
                self.seen_mutations.add(mutated_param_hash)    #add param hash, this is for randomly mutated candidate
                self.save_candidate_param_hash(c) 
            
            cov_rep_status = self.delivery_and_coverage_check(c)
            #1.error-base vuln check is done to check the existence of error files.
            ebc_status = self.error_based_vuln_check(c=c,vuln_func=vuln_func)
            if ebc_status == VulnFuncStatus.VULNERABLE:
                print(f"Vulnerability exploited during error-based vulnerability check.")
                print(f"============ {vulnerability} Fuzzing Without Parameters in Sink Completed =============")
                return VulnFuncStatus.VULNERABLE
            
            '''
            Check the equality of current sink hash with previous hash for early termination because it is likely
            that the sink is a constant
            '''
            if ('::prepare' not in vuln_func[Key.FUNCTION_NAME] and "_prepare" not in vuln_func[Key.FUNCTION_NAME]
                and same_hash_counter < 3):
                func_traces_path = os.path.join("/shared-tmpfs/function-call-traces",f"{c.coverage_id}.json")
                c.function_trace = utils.load_json_file(func_traces_path)
                updated_vuln_func = utils.get_updated_function(vuln_func, c.function_trace)
                if updated_vuln_func:
                    updated_sink = utils.get_sink(updated_vuln_func, vulnerability)
                    updated_sink_hash = hashlib.sha256(str(updated_sink).encode("utf-8")).hexdigest()
                    if prev_sink_hash == updated_sink_hash:
                        prev_sink_hash = updated_sink_hash
                        same_hash_counter += 1
                        if same_hash_counter >= 3:
                            function_traces.update_vuln_func_status_and_remove(vuln_func, c, 
                                                                            VulnFuncStatus.UNRESOLVED)
                            print(f"============== {vulnerability} Fuzzing Without Parameters in Sink Started ==================")
                            return VulnFuncStatus.UNRESOLVED   


            if (mutation_type not in(MutationType.NUMERIC_ZERO, MutationType.EMPTY_STRING) and
                                                    not utils.any_params_length_less_five(c.fuzz_params)):
                if Key.PARAMS_IN_SINK not in vuln_func or not vuln_func[Key.PARAMS_IN_SINK]:
                    if vulnerability == Vulnerability.CODE_EXEC:
                        sink = vuln_func[Key.COMMAND] 
                    elif vulnerability == Vulnerability.PATHTRAVS:
                        sink = vuln_func[Key.PATH]
                    elif vulnerability == Vulnerability.SQLI:
                        sink = vuln_func[Key.QUERY]
                    elif vulnerability == Vulnerability.UNSERIALIZE:
                        sink = vuln_func[Key.SERIALIZED_STRING]
                    elif vulnerability == Vulnerability.XXE:
                        sink = vuln_func[Key.XML_PAYLOAD]

                    vuln_func[Key.PARAMS_IN_SINK] = function_traces.get_params_in_sink(c.fuzz_params, 
                                                                                        sink,
                                                                                        vuln_func,
                                                                                        c.http_target) 
                    if vuln_func[Key.PARAMS_IN_SINK]:
                        if vulnerability == Vulnerability.SQLI:
                            return self.fuzz_sql_function(vuln_func=vuln_func,
                                                        c=c,
                                                        sqli_payloads=payloads_dict)
                        else:
                            return self.generic_fuzz_vuln_function(vuln_func=vuln_func,
                                                                c=c,
                                                                payloads_dict=payloads_dict,
                                                                vulnerability=vulnerability)
            #3.is vuln function executed safely check, only performed after violations payload to uncover bug sent/used. 
            #vuln function need to be updated, see function_based_check
            if vuln_func[Key.NUM_PARAMS_ITERATIONS] >= safeseq_start:
                ssc_status = self.safe_sequence_check(vuln_func=vuln_func,
                                                    c=c,
                                                    vulnerability=vulnerability)
                if ssc_status == VulnFuncStatus.IN_SAFESEQ:
                    print(f"Function is safely executed after {vuln_func[Key.NUM_FUNC_ITERATIONS]} iterations.")
                    print(f"============ {vulnerability} Fuzzing Without Parameters in Sink Completed =================")
                    return VulnFuncStatus.IN_SAFESEQ 
   
            vuln_func[Key.NUM_PARAMS_ITERATIONS] += 1
            vuln_func[Key.NUM_FUNC_ITERATIONS] += 1
            # c.num_iterations += 1
            
        function_traces.update_vuln_func_status_and_remove(vuln_func, c, VulnFuncStatus.UNRESOLVED)
        print(f"============== {vulnerability} Fuzzing Without Parameters in Sink Started ==================")
        return VulnFuncStatus.UNRESOLVED  
    
    def generic_fuzz_vuln_function(self, vuln_func, c, payloads_dict, vulnerability, sanit_rep=None):
        print(f"Fuzzing generic vuln function: {vuln_func[Key.FUNCTION_NAME]}")
        print(f"Location: {vuln_func[Key.FILENAME]}:{vuln_func[Key.LINENO]}")
        print(f"====================== {vulnerability} Fuzzing Started ====================")
        if not sanit_rep:
            sanit_rep = function_traces.sanitation_report(c, vulnerability,vuln_func)
        if sanit_rep:
            for key in sanit_rep:
                if key not in vuln_func[Key.PARAMS_IN_SINK]:
                    #Data type and quoted flag are not applied here, only for functions vulnerable to SQLI
                    vuln_func[Key.PARAMS_IN_SINK][key] = {Key.DATA_TYPE: None,Key.QUOTED: None}
        if Key.PARAMS_IN_SINK in vuln_func and vuln_func[Key.PARAMS_IN_SINK]:
            print(f"Fuzzing vuln function with parameters in sink: {vuln_func[Key.FUNCTION_NAME]}")
            print(f"Location: {vuln_func[Key.FILENAME]}:{vuln_func[Key.LINENO]}")
            len_malformed = len(payloads_dict[KeyPayload.MALFORMED])
            payloads = payloads_dict[KeyPayload.MALFORMED] + payloads_dict[KeyPayload.WELLFORMED]
            len_payloads = len(payloads)
            safeseq_start = len_payloads 
            max_trials = safeseq_start + FuzzParamsIterations.SAFE_SEQ
            
            for key in vuln_func[Key.PARAMS_IN_SINK]:
                key = (key[0],key[1])
                c.mutated_param_type = key[0]
                c.mutated_param_name = key[1]

                vuln_func[Key.NUM_PARAMS_ITERATIONS] = 0 #reset number of iterations for this param to 0
                # while True:
                while vuln_func[Key.NUM_PARAMS_ITERATIONS] < max_trials:
                    if payloads:
                        if vuln_func[Key.NUM_PARAMS_ITERATIONS] < len_payloads:
                            self.mutate_params_using_dictionary(vuln_func, key, MutationType.DICTIONARY, c, 
                                                                            payloads, len_payloads)
                            if sanit_rep and key in sanit_rep:
                                sanit_seq = sanit_rep[key][0]
                                if sanit_seq:
                                    c.fuzz_params[key[0]][key[1]] = function_traces.generate_sanitation_aware_string(
                                                                                sanit_seq,
                                                                                c.fuzz_params[key[0]][key[1]])
                        elif vuln_func[Key.NUM_PARAMS_ITERATIONS] < max_trials:
                            self.mutate_params_randomly(c)
                        
                    mutated_param_hash = utils.get_function_params_hash(vuln_func,c.fixed_params,c.fuzz_params)
                    if mutated_param_hash in self.seen_mutations:
                        vuln_func[Key.NUM_PARAMS_ITERATIONS] += 1
                        vuln_func[Key.NUM_FUNC_ITERATIONS] += 1
                        continue
                    else:
                        self.seen_mutations.add(mutated_param_hash)    #add param hash, this is for randomly mutated candidate
                        self.save_candidate_param_hash(c)

                    cov_rep_status = self.delivery_and_coverage_check(c)
                    
                    #1. Error-based vuln check
                    ebc_status = self.error_based_vuln_check(c=c,vuln_func=vuln_func)
                    if ebc_status == VulnFuncStatus.VULNERABLE:
                        print("Vulnerability exploited via error-based check.")
                        print(f"====================== {vulnerability} Fuzzing Completed ====================")
                        return VulnFuncStatus.VULNERABLE
                    
                    if (vuln_func[Key.NUM_PARAMS_ITERATIONS] < len_payloads):    
                    #2. Function-based vuln check
                        fbc_status = self.function_based_check(vuln_func=vuln_func,
                                                            key=key,
                                                            c=c,
                                                            vulnerability=vulnerability,
                                                            len_payloads=len_payloads)
                        if fbc_status == VulnFuncStatus.VULNERABLE:
                            print("Vulnerability exploited via function-based check.")
                            print(f"====================== {vulnerability} Fuzzing Completed ====================")
                            return VulnFuncStatus.VULNERABLE
                        """    
                        #3. Safe sequence check
                            Notice this check must be performed after c.function_trace is updated. In this code, the
                            update is performed by function_based_check. In other scenario, you must the update function  
                            explicitly before calling sanitation_report. 
                            If yes, activate sanitation-aware mutation
                        """
                    #4. Safe sequence check
                    if vuln_func[Key.NUM_PARAMS_ITERATIONS] >= safeseq_start:
                        ssc_status = self.safe_sequence_check(vuln_func=vuln_func,
                                                                c=c,
                                                                vulnerability=vulnerability)
                        if ssc_status == VulnFuncStatus.IN_SAFESEQ:
                            print("Vulnerable function is executed in safe sequence.")
                            print(f"====================== {vulnerability} Fuzzing Completed ====================")
                            return VulnFuncStatus.IN_SAFESEQ
                    
                    vuln_func[Key.NUM_PARAMS_ITERATIONS] += 1   #total number of params iterations for controlling fuzz parameters loop
                    vuln_func[Key.NUM_FUNC_ITERATIONS] += 1     #total number of function iterations in fuzzing
                    # c.num_iterations += 1 
            function_traces.update_vuln_func_status_and_remove(vuln_func, c, 
                                                               VulnFuncStatus.UNRESOLVED)
            print(f"Completed fuzzing {vulnerability} vulnerable function.")
            print(f"====================== {vulnerability} Fuzzing Started ====================")
            return VulnFuncStatus.UNRESOLVED 
        
        else: #vuln function does not have request parameters in sink
            vf_status = self.fuzz_vuln_func_without_params_in_sink(vuln_func=vuln_func,
                                                                   c=c,
                                                                   payloads_dict=payloads_dict,
                                                                   vulnerability=vulnerability)
            return vf_status
    
    #To fuzz non-sqli vuln function with sanitation report available 
    def fuzz_vuln_func_with_sanitation(self, vuln_func, key, c, vulnerability, payloads, sanit_rep):
        counter = 0
        if sanit_rep:
            sanit_seq = sanit_rep[key][0]
            if not sanit_seq:
                return VulnFuncStatus.FUZZED
        else:
            return VulnFuncStatus.FUZZED
        while counter < len(payloads):
            c.fuzz_params[key[0]][key[1]] = payloads[counter]
            c.fuzz_params[key[0]][key[1]] = function_traces.generate_sanitation_aware_string(sanit_seq,
                                                                         c.fuzz_params[key[0]][key[1]])

            mutated_param_hash = c.get_params_hash()
            if mutated_param_hash in self.seen_mutations:
                counter += 1
                continue
            else:
                self.seen_mutations.add(mutated_param_hash)    #add param hash, this is for randomly mutated candidate
                self.save_candidate_param_hash(c)

            cov_rep_status = self.delivery_and_coverage_check(c)
            
            #1. Error-based vuln check
            ebc_status = self.error_based_vuln_check(c=c,vuln_func=vuln_func)
            if ebc_status == VulnFuncStatus.VULNERABLE:
                return VulnFuncStatus.VULNERABLE
            
            #2. Function-based vuln check
            fbc_status = self.function_based_check(vuln_func=vuln_func,
                                                key=key,
                                                c=c,
                                                vulnerability=vulnerability,
                                                len_payloads=len(payloads))
            if fbc_status == VulnFuncStatus.VULNERABLE:
                return VulnFuncStatus.VULNERABLE
            counter += 1
        return VulnFuncStatus.FUZZED


    # def fuzz_candidate_without_vuln_func(self, c, payloads_dict):
    def fuzz_candidate(self, c, payloads_dict=None):
        print(f"Fuzzing candidate: {c.coverage_id}")
        print(f"====================== Fuzzing Candidate Started ====================")
        if not c.sanitation_report:
            c.sanitation_report = function_traces.sanitation_report(c=c)
            if c.sanitation_report:
                self.save_candidate(c)
        if c.sanitation_report:
            new_vulns = self.fuzz_candidate_with_sanitation(c, c.sanitation_report)
        
        if not c.parameter_comparison_report:
            c.parameter_comparison_report = param_traces.params_comparisons_report(c)
            if c.parameter_comparison_report:
                self.save_candidate(c)
        if c.parameter_comparison_report:
            self.fuzz_candidate_with_params_comparisons(c, c.parameter_comparison_report)

        try_hit_sink_max = FuzzParamsIterations.TRY_HITTING_SINK
        numeric_zero_max = try_hit_sink_max + FuzzParamsIterations.NUMERIC_ZERO
        empty_string_max = numeric_zero_max + FuzzParamsIterations.EMPTY_STRING
        numeric_only_max = empty_string_max + FuzzParamsIterations.NUMERIC_ONLY
        string_only_max = numeric_only_max + FuzzParamsIterations.STRING_ONLY
        type_viol_max = string_only_max + FuzzParamsIterations.TYPE_VIOLATION #use in fuzz_sq;
        domain_viol_max = type_viol_max + FuzzParamsIterations.DOMAIN_VIOLATION #use in fuzz sql
        random_start = domain_viol_max
        max_trials = random_start + FuzzParamsIterations.SAFE_SEQ 

        # while True:
        mutation_type = None
        fuzz_counter = 0
        while fuzz_counter < max_trials:
            if fuzz_counter < try_hit_sink_max:
                mutation_type = MutationType.TRY_HITTING_SINK
                self.mutate_params_with_int_string(c)
            elif fuzz_counter < numeric_zero_max:
                mutation_type = MutationType.NUMERIC_ZERO
                self.mutate_params_with_numeric_zero(c)
            elif fuzz_counter < empty_string_max:
                mutation_type = MutationType.EMPTY_STRING
                self.mutate_params_with_empty_string(c)
            elif fuzz_counter < numeric_only_max:
                mutation_type = MutationType.NUMERIC_ONLY
                self.mutate_params_with_numeric_only(c)
            elif fuzz_counter < string_only_max:
                mutation_type = MutationType.STRING_ONLY
                self.mutate_params_with_string_only(c)
            elif fuzz_counter < domain_viol_max:
                mutation_type = MutationType.DOMAIN_VIOLATION
                self.mutate_params_with_domain_violation(c)
            elif fuzz_counter < max_trials:
                self.mutate_params_randomly(c)

            mutated_param_hash = c.get_params_hash()
            if mutated_param_hash in self.seen_mutations:
                fuzz_counter += 1
                continue
            else:
                self.seen_mutations.add(mutated_param_hash)    #add param hash, this is for randomly mutated candidate
                self.save_candidate_param_hash(c) 
            
            cov_rep_status = self.delivery_and_coverage_check(c)
            #1.error-base vuln check is done to check the existence of error files.
            new_vulns = self.error_based_vuln_check(c=c)

            #2. sanitation check
            if not c.sanitation_report:
                c.sanitation_report = function_traces.sanitation_report(c=c)
                if c.sanitation_report:
                    status = self.fuzz_candidate_with_sanitation(c, c.sanitation_report)

            fuzz_counter += 1
            # c.num_iterations += 1
            if fuzz_counter >= max_trials:
                c.fuzz_status = CandidateStatus.DONE
                print(f"Completed fuzzing candidate: {c.coverage_id}")
                print(f"====================== Fuzzing Candidate Completed ====================")
                return CandidateStatus.DONE
            
    def fuzz_candidate_with_sanitation(self, c, sanit_rep):
        print(f"Sanitation-aware fuzzing for candidate: {c.coverage_id}")
        print(f"====================== Sanitation-aware Fuzzing Started ====================")
        counter = 0
        print(f"Counter = {counter}")
        new_vulns = None
        while counter < FuzzParamsIterations.SANITATION_AWARE:
            #success can happen to only one fuzz_params only, while fail for others
            if sanit_rep:
                success = self.sanitation_aware_mutation(c, sanit_rep)
                print(f"success")
            if not success:
                counter += 1
                print(f"Counter = {counter}")
                continue
            
            mutated_param_hash = c.get_params_hash()
            if mutated_param_hash in self.seen_mutations:
                counter += 1
                print(f"Mutated param has been seen before.")
                print(f"Counter = {counter}")
                continue
            else:
                self.seen_mutations.add(mutated_param_hash)    #add param hash, this is for randomly mutated candidate
                print(f"Mutated added to param_ before.")
                self.save_candidate_param_hash(c)
            
            print(f"Ready to call delivery and coverage check")
            cov_rep_status = self.delivery_and_coverage_check(c)
            print(f"Finish calling calling delivery and coverage check")
            
            #1. Error-based vuln check
            print(f"Ready to call error based vuln check ")
            new_vulns = self.error_based_vuln_check(c)
            print(f"Finish calling calling error based check")
            if new_vulns:
                print("Vulnerability exploited via error-based check.")
                print(f"Completed sanitation-aware fuzzing for candidate: {c.coverage_id}")
                print(f"====================== Sanitation-aware Fuzzing Completed ====================")
                return new_vulns
            counter += 1
        print(f"Completed sanitation-aware fuzzing for candidate: {c.coverage_id}")
        print(f"====================== Sanitation-aware Fuzzing Completed ====================")
        return new_vulns

    def delivery_and_coverage_check(self, c):
        c.parent = c #enable memory efficiency, avoid (deep) copy of candidate/input for each mutation.
                     #have 2 type of parent: itself (for efficiency) and other(different)
                     #consequence: always manipulate parent's attr befire a candidate
        c.parent_cov_id = c.coverage_id #identifies the parent's id and logs
        if c.parent_cov_id.endswith("_"):
            c.parent_cov_id = c.parent_cov_id.strip("_")
        c.num_iterations += 1       #appended to candidate.coverage_id to distinguish all candidates' logs
        if c.coverage_id.endswith(f"_"):
            c.coverage_id = f"{c.coverage_id}{c.num_iterations}"
        else:
            c.coverage_id = f"{c.coverage_id[:c.coverage_id.rfind('_')+1]}{c.num_iterations}"
        self.ff_send_request(c)
        cov_rep_status = self.zimpaf_get_branch_instr_based_coverage(c)
        if (c.api_call_status == APICallTraceStatus.NO_TRACE or 
            c.api_call_status & APICallTraceStatus.INCOMPLETE):
            func_traces_path = os.path.join("/shared-tmpfs/function-call-traces",f"{c.coverage_id}.json")
            c.function_trace = utils.load_json_file(func_traces_path)
            function_traces.set_api_call_trace_status(c,self.ff_vuln_function_hashes)
        return cov_rep_status

    def error_based_vuln_check(self, c, vuln_func=None):
        #1. Error-based vuln check, error can happen in other vuln functions, but the return status only for current
        #   function being fuzzed
        #   If only old bugs or vulns are found, new_vulns is an empty list. 
        new_vulns, zend_exec_status = self.zimpaf_has_vulns(c,VulnCheckType.ERR_BASED, vuln_func=vuln_func)
        if vuln_func:
            #A bug is found in this fuzzing iteration indicated by function is still in c.vuln_functions
            #Therefore, set status to FUZZED
            if utils.is_function_in_list(vuln_func, c.vuln_functions): 
                return VulnFuncStatus.FUZZED
            else:
                #Else, a vulnerability is found indicated by vuln_func does not exist in c.vuln_functions
                #since it has been removed byb self.zimpaf_has_vulns(...)
                #Therefore set status to EXPLOITED
                return VulnFuncStatus.VULNERABLE 
            # new_bug_hit = new_vulns and utils.is_function_in_list(vuln_func, new_vulns)
            # old_bug_hit = not new_vulns and not utils.is_function_in_list(vuln_func, c.vuln_functions)
            # if new_bug_hit or old_bug_hit: #fun
            #     return VulnFuncStatus.FUZZED
            # else:
            #     return VulnFuncStatus.VULNERABLE
        else:
            return new_vulns
        
    def function_based_check(self, vuln_func, key, c, vulnerability,len_payloads):
        func_traces_path = os.path.join("/shared-tmpfs/function-call-traces",f"{c.coverage_id}.json")
        c.function_trace = utils.load_json_file(func_traces_path)
        updated_vuln_func = utils.get_updated_function(vuln_func, c.function_trace)
        c.sanit_functions = function_traces.update_sanitation_functions(c=c, 
                                                                        trace_is_updated=True, 
                                                                        vuln_type=vulnerability)#lazy update sanitation functions,sql only
        if updated_vuln_func and vuln_func[Key.NUM_PARAMS_ITERATIONS] < len_payloads:
            vuln_func.update(updated_vuln_func)
            new_vulns = self.zimpaf_has_vulns(c,VulnCheckType.FUNC_BASED, 
                            vuln_func=vuln_func, param_key_tup=key,vulnerability=vulnerability)
            #new_vuln_hit means vuln_func detail is in new_vulns list
            new_vuln_hit = new_vulns and utils.is_function_in_list(vuln_func, new_vulns)
            #old vuln_hit means  vuln_func is already remove by zimpaf_has_vulns from c.vuln_functions
            old_vuln_hit = not new_vulns and not utils.is_function_in_list(vuln_func, c.vuln_functions)
            if new_vuln_hit or old_vuln_hit:
                return VulnFuncStatus.VULNERABLE
            else:
                return VulnFuncStatus.FUZZED
    
    def safe_sequence_check(self, vuln_func, c, vulnerability):
        func_traces_path = os.path.join("/shared-tmpfs/function-call-traces",f"{c.coverage_id}.json")
        c.function_trace = utils.load_json_file(func_traces_path)
        updated_vuln_func = utils.get_updated_function(vuln_func, c.function_trace)
        if updated_vuln_func:
            vuln_func.update(updated_vuln_func)
            if vulnerability == Vulnerability.SQLI:
                func_exec_safe = function_traces.is_sqli_func_exec_safe(vuln_func,c)
            elif vulnerability == Vulnerability.XXE:
                func_exec_safe = function_traces.is_xxe_func_exec_safe(vuln_func,c)
            else:
                func_exec_safe = function_traces.is_vuln_funcs_executed_in_safe_seq(c,self.start_time,vuln_func)
            if func_exec_safe:
                elapsed = time.time() - self.start_time
                seconds = int(elapsed)
                print(f"\033[32mVULN FUNCTION: {vuln_func[Key.FUNCTION_NAME]} is executed SAFELY. \033[0m")
                print(f"\033[32mDECISION IS MADE AFTER: {seconds} seconds. \033[0m")
                print(f"LOCATION: {vuln_func[Key.FILENAME]}:{vuln_func[Key.LINENO]}")
                function_traces.update_vuln_func_status_and_remove(vuln_func,c,
                                                                   VulnFuncStatus.IN_SAFESEQ)    
                return VulnFuncStatus.IN_SAFESEQ
        else:
            return VulnFuncStatus.FUZZED
    
    def mutate_params_using_dictionary(self, func, key, mttn_type, c, payloads=None, len_payloads=0):
        if mttn_type & MutationType.DICTIONARY and payloads is not None:
            c.fuzz_params[key[0]][key[1]] = payloads[func[Key.NUM_PARAMS_ITERATIONS]]
            # print(c.fuzz_params[key[0]][key[1]])

    def mutate_params_with_int_string(self,c):
        for req_part in c.fuzz_params.keys():
            for param in c.fuzz_params[req_part]:
                c.fuzz_params[req_part][param] = utils.generate_integer_string(7)
    
    def mutate_params_with_numeric_zero(self,c):
        for req_part in c.fuzz_params.keys():
            for param in c.fuzz_params[req_part]:
                c.fuzz_params[req_part][param] = 0
    
    def mutate_params_with_empty_string(self,c):
        for req_part in c.fuzz_params.keys():
            for param in c.fuzz_params[req_part]:
                c.fuzz_params[req_part][param] = ""
    
    def mutate_params_with_numeric_only(self,c):
        for req_part in c.fuzz_params.keys():
            for param in c.fuzz_params[req_part]:
                c.fuzz_params[req_part][param] = utils.generate_random_int()

    def mutate_params_with_string_only(self,c):
        for req_part in c.fuzz_params.keys():
            for param in c.fuzz_params[req_part]:
                c.fuzz_params[req_part][param] = utils.generate_random_string()
    
    def mutate_params_with_domain_violation(self,c, data_type=None):
        for req_part in c.fuzz_params.keys():
            for param in c.fuzz_params[req_part]:
                c.fuzz_params[req_part][param] = utils.generate_domain_violation()
    
    def mutate_params_with_type_violation(self,c, data_type=None):
        for req_part in c.fuzz_params.keys():
            for param in c.fuzz_params[req_part]:
                c.fuzz_params[req_part][param] = utils.generate_type_violation()

    def mutate_params_randomly(self, c):
        for req_part, params in c.fuzz_params.items():
            for param, _ in params.items():
                c.fuzz_params[req_part][param] = utils.generate_type_conformant()
    
    def sanitation_aware_mutation(self,c, sanit_rep, sink_key=None):
        gen_flag = False
        if not sink_key:
            for key in sanit_rep.keys():
                rows = sanit_rep[key]
                for row in rows:
                    c.fuzz_params[key[0]][key[1]] = function_traces.generate_sanitation_aware_string(row)
                    if c.fuzz_params[key[0]][key[1]]:
                        gen_flag = True
        else:
            rows = sanit_rep[sink_key]
            for row in rows:
                c.fuzz_params[sink_key[0]][sink_key[1]] = function_traces.generate_sanitation_aware_string(row)
                if c.fuzz_params[sink_key[0]][sink_key[1]]:
                    gen_flag = True   
        return gen_flag  

    def fuzz_stored_xss(self, c, payloads_dict, vuln_func, func_class=None, sanit_rep=None, type=None):
        print(f"Fuzzing stored XSS injectin in sql vuln function with parameters in sink: {vuln_func[Key.FUNCTION_NAME]}")
        print(f"Location: {vuln_func[Key.FILENAME]}:{vuln_func[Key.LINENO]}")
        print("===================FUZZING STORED XSS IN SQLI VULN FUNCTION===================")
        
        if not sanit_rep:
            sanit_rep = function_traces.sanitation_report(c, Vulnerability.SQLI,vuln_func)
        if sanit_rep:
            for key in sanit_rep:
                if key not in vuln_func[Key.PARAMS_IN_SINK]:
                    #currently, params in sink indentified by sanitation report, cannot identify 
                    # data type and quoted flag
                    vuln_func[Key.PARAMS_IN_SINK][key] = {Key.DATA_TYPE: None,Key.QUOTED: None}
        len_tag = len(payloads_dict[KeyPayload.TAG])
        len_non_tag = len(payloads_dict[KeyPayload.NON_TAG])
        payloads = payloads_dict[KeyPayload.TAG] + payloads_dict[KeyPayload.NON_TAG]
        len_payloads = len_tag + len_non_tag
        max_trials = len_payloads
        # max_trials = len_payloads + FuzzParamsIterations.RANDOM
        if (vuln_func and Key.PARAMS_IN_SINK in vuln_func and vuln_func[Key.PARAMS_IN_SINK]):
            #if prepare function, should be done regardless of params in sink or not
            set_keys =  {key for key in vuln_func[Key.PARAMS_IN_SINK].keys()}

            for key in set_keys:
                key = (key[0],key[1]) #recheck later, why did i write this, seems useless.
                c.mutated_param_type = key[0]
                c.mutated_param_name = key[1]
                tag_payload_flag = None
                is_tag_xss_safe = None
                vuln_func[Key.NUM_PARAMS_ITERATIONS] = 0 #reset number of iterations for this param to 0
                # while True:
                while vuln_func[Key.NUM_PARAMS_ITERATIONS] < max_trials:
                    if payloads:
                        if vuln_func[Key.NUM_PARAMS_ITERATIONS] < len_payloads:
                            self.zimpaf_mutate_sql_function_params(vuln_func, key, MutationType.DICTIONARY, c, payloads, len_payloads)
                            if vuln_func[Key.NUM_PARAMS_ITERATIONS] < len_tag:
                                tag_payload_flag = AllFlags.XSS_PAYLOAD_TAG
                            else:
                                tag_payload_flag = AllFlags.XSS_PAYLOAD_NON_TAG
                        # elif vuln_func[Key.NUM_PARAMS_ITERATIONS] < max_trials:
                        #     self.zimpaf_mutate_sql_function_params(vuln_func, key, MutationType.RANDOM, c) 

                    if sanit_rep and key in sanit_rep:
                        sanit_seq = sanit_rep[key][0]
                        if sanit_seq:
                            c.fuzz_params[key[0]][key[1]] = function_traces.generate_sanitation_aware_string(sanit_seq,
                                                                                        c.fuzz_params[key[0]][key[1]])
                    mutated_param_hash = c.get_params_hash()
                    if mutated_param_hash in self.seen_mutations:
                        vuln_func[Key.NUM_PARAMS_ITERATIONS] += 1 
                        vuln_func[Key.NUM_FUNC_ITERATIONS] += 1
                        continue
                    else:
                        self.seen_mutations.add(mutated_param_hash)    #add param hash, this is for randomly mutated candidate
                        self.save_candidate_param_hash(c) 
                    #this is where instructing server to send response body is important. xss reflected in response body
                    cov_rep_status = self.delivery_and_coverage_check(c) #send_request, check if new path found, if found, create new candidate  
                    #1. Still do error-based vulnerability check by investigating error files, if any, 
                    # This is always done to see if payload trigger more bugs or vulnerability
                    ebc_status = self.error_based_vuln_check(c=c, vuln_func=vuln_func)

                    #2. Stored xss injection attack
                    new_vulns = self.zimpaf_has_vulns(c=c,
                                                       check_type=VulnCheckType.LOGIC_BASED,
                                                       vuln_func=vuln_func,
                                                       param_key_tup=key,
                                                       vulnerability=Vulnerability.XSS,
                                                       logic_vuln_type=LogicVulnType.XSS,
                                                       flags=tag_payload_flag)                                                                                            
                    if new_vulns:
                        print("Completed fuzzing XSS Stored vulnerable function.")
                        print("=================FUZZING STORED XSS COMPLETE====================") 
                        return VulnFuncStatus.VULNERABLE
                    
                    '''
                    This is to check if vulnerable function are executed safely against tag-based xss payloads.
                    '''
                    if vuln_func[Key.NUM_PARAMS_ITERATIONS] < len_tag and is_tag_xss_safe is None:
                        func_traces_path = os.path.join("/shared-tmpfs/function-call-traces",f"{c.coverage_id}.json")
                        c.function_trace = utils.load_json_file(func_traces_path)
                        updated_vuln_func = utils.get_updated_function(vuln_func, c.function_trace)
                        if updated_vuln_func:
                            vuln_func.update(updated_vuln_func)
                            if func_class == Key.SQLI_FUNCTION:
                                is_tag_xss_safe = function_traces.is_safe_from_tags_based_xss(c,vuln_func, Key.QUERY)
                            elif func_class == Key.BIND_EXECUTE_FUNC:
                                is_tag_xss_safe = function_traces.is_safe_from_tags_based_xss(c,vuln_func, Key.PARAMETERS)
                            elif func_class == Key.PATHTRAVS_FUNCTION:
                                is_tag_xss_safe = function_traces.is_safe_from_tags_based_xss(c,vuln_func, Key.PATH)
                            elif func_class == Key.CODE_EXEC_FUNCTION:
                                is_tag_xss_safe = function_traces.is_safe_from_tags_based_xss(c,vuln_func, Key.COMMAND)
                            elif func_class == Key.UNSERIALIZE_FUNCTION:
                                is_tag_xss_safe = function_traces.is_safe_from_tags_based_xss(c,vuln_func, Key.SERIALIZED_STRING)
                            elif func_class == Key.XXE_FUNCTION:
                                is_tag_xss_safe = function_traces.is_safe_from_tags_based_xss(c,vuln_func, Key.XML_PAYLOAD)
                            
                        if is_tag_xss_safe and vuln_func[Key.NUM_PARAMS_ITERATIONS] < len_tag:
                            vuln_func[Key.NUM_PARAMS_ITERATIONS] = len_tag  #skip tag payloads
                            continue
                           
                    # update all iterations counter, at parameter, function, and candidate level.
                    vuln_func[Key.NUM_PARAMS_ITERATIONS] += 1   #total number of params iterations for controlling fuzz parameters loop
                    # vuln_func[Key.NUM_FUNC_ITERATIONS] += 1     #total number of function iterations in fuzzing
                    # c.num_iterations += 1                      #total number of candidate iterations in fuzzing
            function_traces.update_vuln_func_status_and_remove(vuln_func, c, 
                                                               VulnFuncStatus.UNRESOLVED)
            
            '''
            If vuln function is a prepare function, check whether there are parameters land in the sink of its bind/execute
            successor functions. If there are, fuzz it.
            '''
            if ('::prepare' in vuln_func[Key.FUNCTION_NAME] or "_prepare" in vuln_func[Key.FUNCTION_NAME]):
                func_traces_path = os.path.join("/shared-tmpfs/function-call-traces",f"{c.coverage_id}.json")
                c.function_trace = utils.load_json_file(func_traces_path)
                updated_vuln_func = utils.get_updated_function(vuln_func, c.function_trace)
                retval = None
                if updated_vuln_func:
                    retval = updated_vuln_func[Key.RETURN_VALUE]
                if retval:
                    successors = utils.find_successor_function(retval, Key.OBJECT_POINTER, c.function_trace)
                    for successor in successors:
                        if successor and Key.BIND_EXECUTE_FUNC in successor and successor[Key.BIND_EXECUTE_FUNC]:
                            if successor[Key.FUNCTION_NAME] == 'mysqli_stmt_bind_param':
                                param_with_object = successor[Key.PARAMETERS]
                                real_param = param_with_object.split("_",1)[1]
                                params_list.append(real_param)
                                continue
                            params_list.append(successor[Key.PARAMETERS])
                    sink = utils.substitute_placeholders_with_params(updated_vuln_func[Key.QUERY], params_list)
                    if sink:
                        for successor in successors:
                            successor[Key.PARAMS_IN_SINK] = function_traces.get_params_in_sink(c.fuzz_params, sink, 
                                                                                               successor,
                                                                                               c.http_target)
                            if successor.get(Key.PARAMS_IN_SINK):
                                successor[Key.NUM_FUNC_ITERATIONS] = 0
                                retval = self.fuzz_stored_xss(c, payloads_dict, successor, Key.BIND_EXECUTE_FUNC,sanit_rep)
                
            print("Completed fuzzing XSS Stored vulnerable function.")
            print("=================FUZZING STORED XSS COMPLETE====================")
            return VulnFuncStatus.UNRESOLVED 
        
        # Injection can happen using prepare-bind-execute function that is not sanitized agains xss payloads
        # regardless of the inexistence of paramaters land in sink of vulnerable function. The parameters are still 
        # passed to the bind or execute functions.
        else:
            if vuln_func and ('::prepare' in vuln_func[Key.FUNCTION_NAME] or '_prepare' in vuln_func[Key.FUNCTION_NAME]):
                func_traces_path = os.path.join("/shared-tmpfs/function-call-traces",f"{c.coverage_id}.json")
                c.function_trace = utils.load_json_file(func_traces_path)
                updated_vuln_func = utils.get_updated_function(vuln_func, c.function_trace)
                retval = None
                if updated_vuln_func:
                    retval = updated_vuln_func[Key.RETURN_VALUE]
                if retval:
                    successors = utils.find_successor_function(retval, Key.OBJECT_POINTER, c.function_trace)
                    params_list = []
                    for successor in successors:
                        if successor and Key.BIND_EXECUTE_FUNC in successor and successor[Key.BIND_EXECUTE_FUNC]:
                            if successor[Key.FUNCTION_NAME] == 'mysqli_stmt_bind_param':
                                param_with_object = successor[Key.PARAMETERS]
                                real_param = param_with_object.split("_",1)[1]
                                params_list.append(real_param)
                                continue
                            params_list.append(successor[Key.PARAMETERS])
                    sink = utils.substitute_placeholders_with_params(updated_vuln_func[Key.QUERY], 
                                                                                        params_list)
                    if sink:
                        for successor in successors:
                            successor[Key.PARAMS_IN_SINK] = function_traces.get_params_in_sink(
                                                                    c.fuzz_params, sink, successor, c.http_target)
                            if successor.get(Key.PARAMS_IN_SINK):
                                successor[Key.NUM_FUNC_ITERATIONS] = 0
                                retval = self.fuzz_stored_xss(c, payloads_dict, successor, 
                                                                Key.BIND_EXECUTE_FUNC,sanit_rep)    
                    
            print(f"No parameters found in sink for stored XSS fuzzing for function: {vuln_func[Key.FUNCTION_NAME]}")
            function_traces.update_vuln_func_status_and_remove(vuln_func, c, 
                                                               VulnFuncStatus.UNRESOLVED)

            print("Completed fuzzing XSS Stored vulnerable function.")
            print("=================FUZZING STORED XSS COMPLETED====================")
            return VulnFuncStatus.UNRESOLVED 
            

    def fuzz_reflected_xss(self, c, payloads_dict):
        print(f"Fuzzing reflected XSS vulnerability for candidate: {c.coverage_id}.")
        print("==============FUZZING REFLECTED XSS VULNERABILITY STARTED==================")
        
        sanit_flag = False
        max_round = 1
        applied_report = {}
        if not c.sanitation_report:
            c.sanitation_report = function_traces.sanitation_report(c, 
                                     vulnerability= Vulnerability.XSS)
        if c.sanitation_report:
            '''
            filter a row or a sanitation sequence if it contains is_file because is_file always return
            ../../../../../../etc/passwd or a valid file path in the filesystem
            '''
            for key, rows in c.sanitation_report.items():
                new_rows = []           
                for row in rows:
                    remove_row = False
                    for func in row:
                        if func[Key.FUNCTION_NAME] in function_traces.FILESYSTEM_MATCH_FUNCTIONS:
                            remove_row = True
                            break  
                    if not remove_row:
                        new_rows.append(row)
                if new_rows:  
                    applied_report[key] = new_rows  

            if applied_report:
                sanit_flag = True
                max_round = 2
            
        len_tag = len(payloads_dict[KeyPayload.TAG])
        len_non_tag = len(payloads_dict[KeyPayload.NON_TAG])
        payloads = payloads_dict[KeyPayload.TAG] + payloads_dict[KeyPayload.NON_TAG]
        len_payloads = len_tag + len_non_tag
        max_trials = len_payloads
        round = 1
        fuzz_counter = 0
        '''
        If sanitation report existed, this loop runs twice, the first with sanitation and the second without it.
        '''
        while (fuzz_counter < max_trials) and (round <= max_round):
            if payloads and applied_report and sanit_flag:
                 for req_part in c.fuzz_params.keys():
                    for param in c.fuzz_params[req_part]:
                        payload = payloads[fuzz_counter]
                        key = (req_part, param)
                        if key in applied_report:
                            rows = applied_report[key]
                            if rows:
                                idx = random.randint(0,len(rows)-1)
                                row = rows[idx]
                                c.fuzz_params[req_part][param] = function_traces.generate_sanitation_aware_string(row,payload)
                            else:
                                c.fuzz_params[req_part][param] = payload  
                        else:
                            c.fuzz_params[req_part][param] = payloads[fuzz_counter]

            elif payloads and not sanit_flag:
                for req_part in c.fuzz_params.keys():
                    for param in c.fuzz_params[req_part]:
                        c.fuzz_params[req_part][param] = payloads[fuzz_counter]
            
             
            if fuzz_counter < len_tag:
                tag_payload_flag = AllFlags.XSS_PAYLOAD_TAG
            else:
                tag_payload_flag = AllFlags.XSS_PAYLOAD_NON_TAG
                  
            mutated_param_hash = c.get_params_hash()
            if mutated_param_hash in self.seen_mutations:
                fuzz_counter += 1
                if sanit_flag and (fuzz_counter >= max_trials) and (round < max_round):
                    #prepare for second round, fuzzing without applying sanitation.
                    #It is possible that sanitation report exists, but the sanitation does not affect xss payloads.
                    round += 1
                    sanit_flag = False
                    fuzz_counter = 0
                    continue 
                continue
            else:
                self.seen_mutations.add(mutated_param_hash)    #add param hash, this is for randomly mutated candidate
                self.save_candidate_param_hash(c) 
            #this is where instructing server to send response body is important. xss reflected in response body
            cov_rep_status = self.delivery_and_coverage_check(c) #send_request, check if new path found, if found, create new candidate
            #1.error-based vulnerability check by investigating error files, if any, this is always done regardless of which payload used
            
            new_vulns = self.zimpaf_has_vulns(c=c,
                                                check_type=VulnCheckType.LOGIC_BASED,
                                                vulnerability=Vulnerability.XSS,
                                                logic_vuln_type=LogicVulnType.XSS,
                                                flags=tag_payload_flag)                                                                                            
            if new_vulns:
                print("Completed fuzzing for Reflected XSS vulnerability.")
                print("==============FUZZING REFLECTED XSS VULNERABILITY COMPLETED==================")
                return VulnFuncStatus.VULNERABLE
            
            fuzz_counter += 1
            #if sanitation report exists, prepare for second round, fuzzing without applying sanitation.
            if sanit_flag and (fuzz_counter >= max_trials) and (round < max_round):
                round += 1
                sanit_flag = False
                fuzz_counter = 0
                continue
        print("Completed fuzzing for Reflected XSS vulnerability.")
        print("==============FUZZING REFLECTED XSS VULNERABILITY COMPLETED==================")
        return VulnFuncStatus.UNRESOLVED 
    
    def fuzz_candidate_with_params_comparisons(self, c, params_comparisons_report):
        print(f"Parameter comparison-aware fuzzing for candidate: {c.coverage_id}")
        print(f"====================== Parameter comparison-aware Fuzzing Started ====================")
       
        new_vulns = None
        if c.http_method.upper() == HttpMethod.POST:
            out_key = Key.BODY_PARAMS
        elif c.http_method.upper() == HttpMethod.GET:
            out_key = Key.QUERY_PARAMS
        if not params_comparisons_report:
            return 
        val_1 = None
        val_2 = None
        for param_cmp_rep in params_comparisons_report:
            #this guards error in param_comparison_mutation_flipping(...)
            if not utils.params_in_branches_in_fuzz_params(param_cmp_rep, out_key, c.fuzz_params):
                continue
            #store the value first because it will be flipped and must be restored for next params in branches
            key1 = param_cmp_rep.get(Key.OP1_INPUT_PARAM)
            key2 = param_cmp_rep.get(Key.OP2_INPUT_PARAM)
            if key1 and out_key in c.fuzz_params and key1 in c.fuzz_params[out_key]:
                val_1 = c.fuzz_params[out_key][key1]
            elif key1 and out_key in c.fixed_params and key1 in c.fixed_params[out_key]:
                val_1 = c.fixed_params[out_key][key1]

            if key2 and out_key in c.fuzz_params and key2 in c.fuzz_params[out_key]:
                val_2 = c.fuzz_params[out_key][key2]
            elif key2 and out_key in c.fixed_params and key2 in c.fixed_params[out_key]:
                val_2 = c.fixed_params[out_key][key2]

            param_traces.param_comparison_mutation_flipping(c,param_cmp_rep)
            #consider removing param_cmp_rep to make program more efficient
            if param_cmp_rep.get(Key.OP1_INPUT_PARAM):    
                c.mutated_parameter_comparison.append((out_key,param_cmp_rep.get(Key.OP1_INPUT_PARAM)))
            if param_cmp_rep.get(Key.OP2_INPUT_PARAM):
                c.mutated_parameter_comparison.append((out_key, param_cmp_rep.get(Key.OP2_INPUT_PARAM)))

            cov_rep_status = self.delivery_and_coverage_check(c)
            if cov_rep_status == CoverageReportStatus.NEW_PATH:
                print(f"New path is found and new candidate is created")
                
            #1. Error-based vuln check
            new_vulns = self.error_based_vuln_check(c)

            #restore the mutated key in param_cmp_rep
            if val_1 is not None:
                if key1 in c.fixed_params[out_key]:
                    c.fixed_params[out_key][key1] = val_1
                elif key1 in c.fuzz_params[out_key]:
                    c.fuzz_params[out_key][key1] = val_1

            if val_2 is not None:
                if key2 in c.fixed_params[out_key]:
                    c.fixed_params[out_key][key2] = val_2
                elif key2 in c.fuzz_params[out_key]:
                    c.fuzz_params[out_key][key2] = val_2

        print(f"Completed fuzzing with parameter comparison mutation: {c.coverage_id}")
        print(f"====================== Parameter comparison-aware Fuzzing Completed ====================")
        return
             
if __name__ == "__main__":
    #time.sleep(10)
    #Add statements to check if zimpaf is active/running.

    if "FUZZER_SEED" in os.environ:
        random_seed = int(os.environ["FUZZER_SEED"])
    else:
        random_seed = int.from_bytes(os.urandom(4), byteorder="little")
    random.seed(random_seed)

    if not "FUZZER_NODE_ID" in os.environ:
        os.environ["FUZZER_NODE_ID"] = "1"

    if not "FUZZER_CLEANUP" in os.environ:
        os.environ["FUZZER_CLEANUP"] = "1"

    if not "FUZZER_COMPRESS" in os.environ:
        os.environ["FUZZER_COMPRESS"] = "0"

    if not "FUZZER_CONFIG" in os.environ:
        # sys.exit("No config provided in ENV FUZZER_CONFIG")

        #The Benchmark Web Application Test Cases

         #PoC Web Application
        # os.environ["FUZZER_CONFIG"] = "poctest/mysqli_real_escape_sanit"
        os.environ["FUZZER_CONFIG"] = "poctest/seq_sanitation"            #Motivating Example-Listing 1 annotated (7) and (9)
        # os.environ["FUZZER_CONFIG"] = "poctest/domain_violation"          #hit
        # os.environ["FUZZER_CONFIG"] = "poctest/safe_sequence"
        # os.environ["FUZZER_CONFIG"] = "poctest/param_in_branch"
        # os.environ["FUZZER_CONFIG"] = "poctest/instrumentation_test_1"    #To compare ZIMPAF and PCOV+UOPZ instrumetation
        # os.environ["FUZZER_CONFIG"] = "poctest/instrumentation_test_2"    #As ZIMPAF is designed to log actual execution, mutation can produce large max counter,
                                                                            #making fuzzing lasts for a long time


        # DVWA
        # os.environ["FUZZER_CONFIG"] = "dvwa/sqli_low"
        # os.environ["FUZZER_CONFIG"] = "dvwa/sqli_med"
        # os.environ["FUZZER_CONFIG"] = "dvwa/sqli_imposs"
        # os.environ["FUZZER_CONFIG"] = "dvwa/sqli_blind_low"
        # os.environ["FUZZER_CONFIG"] = "dvwa/sqli_blind_med"
        # os.environ["FUZZER_CONFIG"] = "dvwa/sqli_blind_high"
        # os.environ["FUZZER_CONFIG"] = "dvwa/rce_low"
        # os.environ["FUZZER_CONFIG"] = "dvwa/rce_med"
        # os.environ["FUZZER_CONFIG"] = "dvwa/rce_high"
        # os.environ["FUZZER_CONFIG"] = "dvwa/rce_imposs"
        # os.environ["FUZZER_CONFIG"] = "dvwa/fi_low"
        # os.environ["FUZZER_CONFIG"] = "dvwa/fi_med"
        # os.environ["FUZZER_CONFIG"] = "dvwa/fi_high"
        # os.environ["FUZZER_CONFIG"] = "dvwa/fi_imposs"
        # os.environ["FUZZER_CONFIG"] = "dvwa/xss_s_low"
        # os.environ["FUZZER_CONFIG"] = "dvwa/xss_s_med"
        # os.environ["FUZZER_CONFIG"] = "dvwa/xss_s_high"
        # os.environ["FUZZER_CONFIG"] = "dvwa/xss_s_imposs"
        # os.environ["FUZZER_CONFIG"] = "dvwa/xss_r_low"
        # os.environ["FUZZER_CONFIG"] = "dvwa/xss_r_med"
        # os.environ["FUZZER_CONFIG"] = "dvwa/xss_r_high"
        # os.environ["FUZZER_CONFIG"] = "dvwa/xss_r_imposs"


        #BWAPP
        # os.environ["FUZZER_CONFIG"] = "bwapp/commandi"
        # os.environ["FUZZER_CONFIG"] = "bwapp/commandi_blind"
        # os.environ["FUZZER_CONFIG"] = "bwapp/pathtraversal"               #allow only well-formed input
        # os.environ["FUZZER_CONFIG"] = "bwapp/phpi"
        # os.environ["FUZZER_CONFIG"] = "bwapp/rlfi"
        # os.environ["FUZZER_CONFIG"] = "bwapp/sqli_ajax"          
        # os.environ["FUZZER_CONFIG"] = "bwapp/sqli_blind_boolean"
        # os.environ["FUZZER_CONFIG"] = "bwapp/sqli_blind_time"
        # os.environ["FUZZER_CONFIG"] = "bwapp/sqli_get_search"
        # os.environ["FUZZER_CONFIG"] = "bwapp/sqli_get_select"
        # os.environ["FUZZER_CONFIG"] = "bwapp/sqli_login_hero"
        # os.environ["FUZZER_CONFIG"] = "bwapp/sqli_post_search"
        # os.environ["FUZZER_CONFIG"] = "bwapp/sqli_post_select"
        # os.environ["FUZZER_CONFIG"] = "bwapp/sqli_stored_blog" 
        # os.environ["FUZZER_CONFIG"] = "bwapp/xss_ajax"
        # os.environ["FUZZER_CONFIG"] = "bwapp/xss_back_button"
        # os.environ["FUZZER_CONFIG"] = "bwapp/xss_custom_header"
        # os.environ["FUZZER_CONFIG"] = "bwapp/xss_eval"
        # os.environ["FUZZER_CONFIG"] = "bwapp/xss_get"
        # os.environ["FUZZER_CONFIG"] = "bwapp/xss_href"
        # os.environ["FUZZER_CONFIG"] = "bwapp/xss_json"
        # os.environ["FUZZER_CONFIG"] = "bwapp/xss_post"
        # os.environ["FUZZER_CONFIG"] = "bwapp/xss_referer"
        # os.environ["FUZZER_CONFIG"] = "bwapp/xss_stored"
        # os.environ["FUZZER_CONFIG"] = "bwapp/xss_useragent"


        #  wackopicko
        # os.environ["FUZZER_CONFIG"] = "wackopicko/admin"
        # os.environ["FUZZER_CONFIG"] = "wackopicko/guestbook"
        # os.environ["FUZZER_CONFIG"] = "wackopicko/login"
        # os.environ["FUZZER_CONFIG"] = "wackopicko/passcheck"
        # os.environ["FUZZER_CONFIG"] = "wackopicko/piccheck"
        # os.environ["FUZZER_CONFIG"] = "wackopicko/register_1"
        # os.environ["FUZZER_CONFIG"] = "wackopicko/search"
        # os.environ["FUZZER_CONFIG"] = "wackopicko/submitname"
        # os.environ["FUZZER_CONFIG"] = "wackopicko/upload_1"


        #XVWA
        # os.environ["FUZZER_CONFIG"] = "xvwa/fi"
        # os.environ["FUZZER_CONFIG"] = "xvwa/phpobject"
        # os.environ["FUZZER_CONFIG"] = "xvwa/rce"
        # os.environ["FUZZER_CONFIG"] = "xvwa/sqli_blind_item"
        # os.environ["FUZZER_CONFIG"] = "xvwa/sqli_blind_search"
        # os.environ["FUZZER_CONFIG"] = "xvwa/sqli_item"
        # os.environ["FUZZER_CONFIG"] = "xvwa/sqli_search"
        # os.environ["FUZZER_CONFIG"] = "xvwa/xss_stored"
        # os.environ["FUZZER_CONFIG"] = "xvwa/xss_reflected" 

        #WordPress, wp plugins having open redirect vulnerabilities are not included, out of scope of the research
        #Can be included by adding detection capability for header function in RedPhuzz as ZIMPAF already monitored that function
        # os.environ["FUZZER_CONFIG"] = "wordpress/arprice-responsive-pricing-table"
        # os.environ["FUZZER_CONFIG"] = "wordpress/crm-perks-forms"
        # os.environ["FUZZER_CONFIG"] = "wordpress/essential-real-estate"
        # os.environ["FUZZER_CONFIG"] = "wordpress/gallery-album"
        # os.environ["FUZZER_CONFIG"] = "wordpress/hypercomments"
        # os.environ["FUZZER_CONFIG"] = "wordpress/joomsport-sports-league-results-management"
        # os.environ["FUZZER_CONFIG"] = "wordpress/kivicare-clinic-management-system" 
        # os.environ["FUZZER_CONFIG"] = "wordpress/nirweb-support"                             i
        # os.environ["FUZZER_CONFIG"] = "wordpress/nmedia-user-file-uploader"
        # os.environ["FUZZER_CONFIG"] = "wordpress/photo-gallery"     
        # os.environ["FUZZER_CONFIG"] = "wordpress/rezgo"
        # os.environ["FUZZER_CONFIG"] = "wordpress/show-all-comments-in-one-page"
        # os.environ["FUZZER_CONFIG"] = "wordpress/totop-link"
        # os.environ["FUZZER_CONFIG"] = "wordpress/ubigeo-peru"
        # os.environ["FUZZER_CONFIG"] = "wordpress/udraw"
        # os.environ["FUZZER_CONFIG"] = "wordpress/usc-e-shop"

    fuzzer = Fuzzer(fuzzer_id=os.environ['FUZZER_NODE_ID'])
    fuzzer.load_config(os.environ['FUZZER_CONFIG'])
    fuzzer.run()


