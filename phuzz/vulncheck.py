import html
import os

import bleach
import esprima
import re
import urllib.parse as urlparse
import json
from bs4 import BeautifulSoup, element
from difflib import SequenceMatcher
from utils import fuzz_open
import utils

class VulnCheck():
    NAME = "Example"

    def check(self, candidate):
        return False



# Code for this class taken from https://github.com/ovanr/webFuzz/blob/v1.2.0/webFuzz/webFuzz/
class WebFuzzXSSVulnCheck(VulnCheck):
    NAME = "WebFuzzXSSVulnCheck"
    CONFIDENCE_NONE = 0
    CONFIDENCE_LOW = 1
    CONFIDENCE_HIGH = 2

    URLATTRIBUTES = [
        "action",
        "cite",
        "data",
        "formaction",
        "href",
        "longdesc",
        "manifest",
        "poster",
        "src"
    ]

    FLAGGED_ELEMENTS = {
        CONFIDENCE_NONE: {},
        CONFIDENCE_LOW: {},
        CONFIDENCE_HIGH: {},
    }

    xss_count = 0 #added by tennov to solve the error in original code on line


    def _webfuzz_misc_longest_str_match(self, haystack, needle):
        # https://github.com/ovanr/webFuzz/blob/v1.2.0/webFuzz/webFuzz/misc.py#L101
        match = SequenceMatcher(None, haystack, needle)
        (_,__,size) = match.find_longest_match(0, len(haystack), 0, len(needle))
        return size


    def _webfuzz_xss_precheck(self, candidate):
        # Taken from WebFuzz
        # https://github.com/ovanr/webFuzz/blob/v1.2.0/webFuzz/webFuzz/detector.py#L149

        raw_html = candidate.response.text
        if self._webfuzz_misc_longest_str_match(raw_html, "0xdeadbeef") >= 5:
            return True
        else:
            return False

    def _webfuzz_xss_record_response(self, candidate, confidence, id_, elem_type, value):

        if confidence == WebFuzzXSSVulnCheck.CONFIDENCE_NONE:
            return

        candidate_url = candidate.response.url

        if candidate_url not in WebFuzzXSSVulnCheck.FLAGGED_ELEMENTS[confidence]:
            WebFuzzXSSVulnCheck.FLAGGED_ELEMENTS[confidence][candidate_url] = set()

        if id_ not in WebFuzzXSSVulnCheck.FLAGGED_ELEMENTS[confidence][candidate_url]:

            if not WebFuzzXSSVulnCheck.FLAGGED_ELEMENTS[WebFuzzXSSVulnCheck.CONFIDENCE_HIGH].get(candidate_url, []):
                if confidence == WebFuzzXSSVulnCheck.CONFIDENCE_HIGH:
                    # self.xss_count += 1
                    WebFuzzXSSVulnCheck.xss_count += 1

            WebFuzzXSSVulnCheck.FLAGGED_ELEMENTS[confidence][candidate_url].add(id_)

            # if node.is_mutated:
            #     # reward parent node with a sink found
            #     node.parent_request.has_sinks = True


    def _webfuzz_xss_should_analyze(self, id_, url, elem_content):
        if id_ not in WebFuzzXSSVulnCheck.FLAGGED_ELEMENTS[WebFuzzXSSVulnCheck.CONFIDENCE_HIGH].get(url, []) and \
            self._webfuzz_misc_longest_str_match(elem_content, "0xdeadbeef") >= 5:
            return True
        else:
            return False

    def _webfuzz_xss_js_ast_traversal(self, node):
        confidence = WebFuzzXSSVulnCheck.CONFIDENCE_NONE

        if type(node) == list:
            for stmt in node:
                res = self._webfuzz_xss_js_ast_traversal(stmt)
                if res == WebFuzzXSSVulnCheck.CONFIDENCE_HIGH:
                    return WebFuzzXSSVulnCheck.CONFIDENCE_HIGH
                else:
                    confidence = max(res, confidence)

        elif 'esprima.nodes.CallExpression' in str(type(node)):
             if node.callee.name in ["alert", "prompt", "confirm"]:

                res = self._webfuzz_xss_js_ast_traversal(node.arguments)
                if res > WebFuzzXSSVulnCheck.CONFIDENCE_NONE:
                    # 0xdeadbeef found in one of its arguments
                    return WebFuzzXSSVulnCheck.CONFIDENCE_HIGH
                else:
                    confidence = max(res, confidence)

        elif 'esprima.nodes.TaggedTemplateExpression' in str(type(node)):
            if node.quasi.type == 'TemplateLiteral' and \
               node.tag.name in ["alert", "prompt", "confirm"]:

                res = self._webfuzz_xss_js_ast_traversal(node.quasi.quasis)
                if res > WebFuzzXSSVulnCheck.CONFIDENCE_NONE:
                    # 0xdeadbeef found in one of its arguments
                    return WebFuzzXSSVulnCheck.CONFIDENCE_HIGH
                else:
                    confidence = max(res, confidence)

        if "esprima.nodes" in str(type(node)):
            for attr in dir(node):
                res = self._webfuzz_xss_js_ast_traversal(getattr(node, attr))
                if res == WebFuzzXSSVulnCheck.CONFIDENCE_HIGH:
                    return WebFuzzXSSVulnCheck.CONFIDENCE_HIGH
                else:
                    confidence = max(res, confidence)

        if type(node) == str:
            # if longest_str_match(node, "0xdeadbeef") >= 5:
            if self._webfuzz_misc_longest_str_match(node, "0xdeadbeef") >= 5:
                confidence = max(WebFuzzXSSVulnCheck.CONFIDENCE_LOW, confidence)

        return confidence

    def _webfuzz_xss_handle_script(self, elem_content):
        try:
            script = esprima.parseScript(elem_content)
            return self._webfuzz_xss_js_ast_traversal(script.body)
        except:
            # fallback to weak method
            if self._webfuzz_misc_longest_str_match(elem_content, "0xdeadbeef") >= 5:
                return WebFuzzXSSVulnCheck.CONFIDENCE_LOW
            
            return WebFuzzXSSVulnCheck.CONFIDENCE_NONE

    def _webfuzz_xss_handle_attr(self, attr_name, attr_content):
        result = WebFuzzXSSVulnCheck.CONFIDENCE_LOW

        if attr_name.lower() in WebFuzzXSSVulnCheck.URLATTRIBUTES and \
           attr_content[:11].lower() == "javascript:":
            # strip leading javascript
            attr_content = attr_content[11:]
            result = self._webfuzz_xss_handle_script(attr_content)

        elif attr_name[:2] == "on":
            result = self._webfuzz_xss_handle_script(attr_content)

        return result

    def _webfuzz_xss_scanner(self, candidate):
        # Taken from Webfuzz
        # https://github.com/ovanr/webFuzz/blob/4e8da2aa80f932cc0f7c05212620b24654a3092c/webFuzz/webFuzz/detector.py#L154

        raw_html = candidate.response.text
        bsoup = BeautifulSoup(raw_html, "html.parser")
        confidence = WebFuzzXSSVulnCheck.CONFIDENCE_NONE

        for elem in bsoup.find_all():
            if type(elem) != element.Tag:
                continue

            id_ = elem.name + "/" + elem.attrs.get('id', "")

            if elem.name == "script":
                if not self._webfuzz_xss_should_analyze(id_, candidate.response.url, elem.text):
                    continue

                result = self._webfuzz_xss_handle_script(elem.text)

                self._webfuzz_xss_record_response(candidate, result, id_, elem_type="Script", value=elem.text)

                confidence = max(result, confidence)

            for (attr_name, attr_value) in elem.attrs.items():
                param_id = id_ + "/" + attr_name
                if not self._webfuzz_xss_should_analyze(param_id, candidate.response.url, attr_value):
                    continue

                result = self._webfuzz_xss_handle_attr(attr_name, attr_value)
                self._webfuzz_xss_record_response(candidate, result, param_id, elem_type=f"Attribute {attr_name}", value=attr_value)

                confidence = max(result, confidence)

        return confidence
    
    def check(self, candidate):
        if not candidate.response:
            return False
        
        #this comment is added by tennov to explain more about this code and related code
        #to check for xss stored and reflecte by inspecting full reflection of payload/fuzz_params in response.


        # Taken from WebFuzz
        # https://github.com/ovanr/webFuzz/blob/v1.2.0/webFuzz/webFuzz/worker.py#L126
        if not self._webfuzz_xss_precheck(candidate):
            return False

        if self._webfuzz_xss_scanner(candidate) > WebFuzzXSSVulnCheck.CONFIDENCE_NONE:
            return True

        return False


class XSSVulnCheck(VulnCheck):
    NAME = "XSS"

    def check(self, candidate):
        if not candidate.response:
            return False
        for param_type in ['query_params', 'body_params', 'headers', 'cookies']:
            for param in candidate.fuzz_params[param_type].items():
                if html.unescape(bleach.clean(param[1], strip=True)) != param[1]:
                    if candidate.response.text.find(param[1]) != -1:
                        candidate.vulns.append(self.NAME)
                        return True
        return False


class SQLiVulnCheck(VulnCheck):
    NAME = "SQLi"

    def __init__(self, mysql_errors_folder):
        self.mysql_errors_folder = mysql_errors_folder

    def check(self, candidate):
        sqli_file = os.path.join(
            self.mysql_errors_folder, f"{candidate.coverage_id}.json"
        )
        if os.path.isfile(sqli_file):
            return True
        return False

class ParamBasedSQLiVulnCheck(VulnCheck):
    NAME = "SQLi"

    def __init__(self, mysql_errors_folder):
        self.mysql_errors_folder = mysql_errors_folder

    def check(self, candidate):
        sqli_file = os.path.join(
            self.mysql_errors_folder, f"{candidate.coverage_id}.json"
        )
        if not os.path.isfile(sqli_file):
            return False

        for line in fuzz_open(sqli_file):
            if not line.strip():
                continue
            error = json.loads(line)
            for error_param in error['params']:
                if not error_param:
                    continue
                for vuln_type in candidate.fuzz_params.keys():
                    for pkey, pval in candidate.fuzz_params[vuln_type].items():
                        if pval in error_param:
                            return True
        return False


class CommandInjectionVulnCheck(VulnCheck):
    NAME = "CommandInjection"

    def __init__(self, shell_errors_folder):
        self.shell_errors_folder = shell_errors_folder

    def check(self, candidate):
        cmd_injection_file = os.path.join(
            self.shell_errors_folder, f"{candidate.coverage_id}.json"
        )
        if os.path.isfile(cmd_injection_file):
            return True
        return False

class ParamBasedCommandInjectionVulnCheck(VulnCheck):
    NAME = "CommandInjection"

    def __init__(self, shell_errors_folder):
        self.shell_errors_folder = shell_errors_folder

    def check(self, candidate):
        cmd_injection_file = os.path.join(
            self.shell_errors_folder, f"{candidate.coverage_id}.json"
        )
        if not os.path.isfile(cmd_injection_file):
            return False

        for line in fuzz_open(cmd_injection_file):
            if not line.strip():
                continue
            error = json.loads(line)
            for error_param in error['params']:
                if not error_param:
                    continue
                for vuln_type in candidate.fuzz_params.keys():
                    for pkey, pval in candidate.fuzz_params[vuln_type].items():
                        if pval in error_param:
                            return True
        return False

class UnserializeVulnCheck(VulnCheck):
    NAME = "Unserialize"

    def __init__(self, unserialize_errors_folder):
        self.unserialize_errors_folder = unserialize_errors_folder

    def check(self, candidate):
        unserialize_file = os.path.join(
            self.unserialize_errors_folder, f"{candidate.coverage_id}.json"
        )
        if os.path.isfile(unserialize_file):
            return True
        return False

class ParamBasedUnserializeVulnCheck(VulnCheck):
    NAME = "Unserialize"

    def __init__(self, unserialize_errors_folder):
        self.unserialize_errors_folder = unserialize_errors_folder

    def check(self, candidate):
        unserialize_file = os.path.join(
            self.unserialize_errors_folder, f"{candidate.coverage_id}.json"
        )
        if not os.path.isfile(unserialize_file):
            return False

        for line in fuzz_open(unserialize_file):
            if not line.strip():
                continue
            error = json.loads(line)
            for error_param in error['params']:
                if not error_param:
                    continue
                for vuln_type in candidate.fuzz_params.keys():
                    for pkey, pval in candidate.fuzz_params[vuln_type].items():
                        if pval in error_param:
                            return True
        return False

class PathTraversalVulnCheck(VulnCheck):
    NAME = "PathTraversal"

    def __init__(self, pathtraversal_errors_folder):
        self.pathtraversal_errors_folder = pathtraversal_errors_folder

    def check(self, candidate):
        pathtraversal_file = os.path.join(
            self.pathtraversal_errors_folder, f"{candidate.coverage_id}.json"
        )
        if os.path.isfile(pathtraversal_file):
            return True
        return False

class ParamBasedPathTraversalVulnCheck(VulnCheck):
    NAME = "PathTraversal"

    def __init__(self, pathtraversal_errors_folder):
        self.pathtraversal_errors_folder = pathtraversal_errors_folder

    def check(self, candidate):
        pathtraversal_file = os.path.join(
            self.pathtraversal_errors_folder, f"{candidate.coverage_id}.json"
        )
        if not os.path.isfile(pathtraversal_file):
            return False

        for line in fuzz_open(pathtraversal_file):
            if not line.strip():
                continue
            error = json.loads(line)
            for error_param in error['params']:
                if not error_param:
                    continue
                for vuln_type in candidate.fuzz_params.keys():
                    for pkey, pval in candidate.fuzz_params[vuln_type].items():
                        if pval in error_param:
                            return True
        return False

class WebPathBasedPathTraversalVulnCheck(VulnCheck):
    NAME = "PathTraversal"

    def __init__(self, pathtraversal_errors_folder):
        self.pathtraversal_errors_folder = pathtraversal_errors_folder
        self.web_paths = []
        with open(os.path.join("/shared-tmpfs", "web-paths.txt")) as f:
            for path in f:
                self.web_paths.append(path.strip())

    def check(self, candidate):
        pathtraversal_file = os.path.join(
            self.pathtraversal_errors_folder, f"{candidate.coverage_id}.json"
        )
        if not os.path.isfile(pathtraversal_file):
            return False

        for line in fuzz_open(pathtraversal_file):
            if not line.strip():
                continue
            error = json.loads(line)
            for error_param in error['params']:
                if not error_param:
                    continue
                if error_param in self.web_paths:
                    continue
                for vuln_type in candidate.fuzz_params.keys():
                    for pkey, pval in candidate.fuzz_params[vuln_type].items():
                        if pval in error_param:
                            return True
        return False

class OpenRedirectVulnCheck(VulnCheck):
    NAME = "OpenRedirect"

    def check(self, candidate):
        if candidate.response is None:
            return False

        respones = candidate.response.history if candidate.response.history else [candidate.response]
        for resp in respones:
            if 300 <= resp.status_code < 400 and 'Location' in resp.headers:
                dest_url = resp.headers['Location']
                dest_url_parts = list(urlparse.urlparse(dest_url))
                for vuln_type in candidate.fuzz_params.keys():
                    for pkey, pval in candidate.fuzz_params[vuln_type].items():
                        pval_parts = list(urlparse.urlparse(pval))
                        if pval == dest_url or pval in dest_url_parts or dest_url_parts == pval_parts:
                            return True
                            # This could be more sophisticated, but should be sufficient for easy open redirects.
        
        return False

class XXEVulnCheck(VulnCheck):
    NAME = "XXE"

    def __init__(self, xxe_errors_folder):
        self.xxe_errors_folder = xxe_errors_folder

    def check(self, candidate):
        xxe_file = os.path.join(
            self.xxe_errors_folder, f"{candidate.coverage_id}.json"
        )
        if os.path.isfile(xxe_file):
            return True
        return False

class ParamBasedXXEVulnCheck(VulnCheck):
    NAME = "XXE"

    def __init__(self, xxe_errors_folder):
        self.xxe_errors_folder = xxe_errors_folder

    def check(self, candidate):
        xxe_file = os.path.join(
            self.xxe_errors_folder, f"{candidate.coverage_id}.json"
        )
        if not os.path.isfile(xxe_file):
            return False

        for line in fuzz_open(xxe_file):
            if not line.strip():
                continue
            error = json.loads(line)
            for error_param in error['params']:
                if not error_param:
                    continue
                for vuln_type in candidate.fuzz_params.keys():
                    for pkey, pval in candidate.fuzz_params[vuln_type].items():
                        if pval in error_param:
                            return True
        return False


class VulnChecker():
    def __init__(self):
        self.vuln_checkers = []

    def vuln_check(self, candidate):
        pass


class DefaultVulnChecker(VulnChecker):
    def __init__(self, mysql_errors_folder=None, shell_errors_folder=None, unserialize_errors_folder=None, pathtraversal_errors_folder=None, xxe_errors_folder=None):
        super(VulnChecker, self).__init__()
        self.vuln_checkers = [
            WebFuzzXSSVulnCheck(),
            SQLiVulnCheck(mysql_errors_folder),
            CommandInjectionVulnCheck(shell_errors_folder),
            UnserializeVulnCheck(unserialize_errors_folder),
            PathTraversalVulnCheck(pathtraversal_errors_folder),
            OpenRedirectVulnCheck(),
            XXEVulnCheck(xxe_errors_folder)
        ]

    def vuln_check(self, candidate):
        vulns = []
        for vuln_check in self.vuln_checkers:
            if vuln_check.check(candidate):
                vulns.append(vuln_check.NAME)
        return vulns

class ParamBasedVulnChecker(DefaultVulnChecker):
    def __init__(self, mysql_errors_folder=None, shell_errors_folder=None, unserialize_errors_folder=None, pathtraversal_errors_folder=None, xxe_errors_folder=None):
        self.vuln_checkers = [
            WebFuzzXSSVulnCheck(),
            ParamBasedSQLiVulnCheck(mysql_errors_folder),
            ParamBasedCommandInjectionVulnCheck(shell_errors_folder),
            ParamBasedUnserializeVulnCheck(unserialize_errors_folder),
            #ParamBasedPathTraversalVulnCheck(pathtraversal_errors_folder), # This one was used during the main analysis -> it discovered 'fu' in 'functions.php' (Wordpress), which is a false positive. 
            WebPathBasedPathTraversalVulnCheck(pathtraversal_errors_folder), # This one ignores existing files, such as functions.php, and should thus report less false positives.
            OpenRedirectVulnCheck(),
            ParamBasedXXEVulnCheck(xxe_errors_folder)
        ]


#The code below is written by tennov
from libzimpaf import function_traces
from libzimpaf.constants import (SoftwareFlaw,Vulnerability, FATAL_ERROR_FLAGS, AllFlags,
                                 ZendExecStatus, APICallTraceStatus, Key, ThrowableType, LogicVulnType,
                                 FlawChecker)

class ZimpafVulnChecker(VulnChecker):
    def __init__(self, mysql_errors_folder=None, shell_errors_folder=None, 
                        error_files_folder=None, exception_files_folder=None):
        super().__init__()
        self.mysql_errors_folder = mysql_errors_folder
        self.shell_errors_folder = shell_errors_folder
        self.error_files_folder = error_files_folder
        self.exception_files_folder = exception_files_folder
        self.xss_vulnchecker = WebFuzzXSSVulnCheck()
        self.open_redirect_vulnchecker = OpenRedirectVulnCheck()

    def vuln_check(self, candidate, logic_vuln_check=False, response_check=False, vuln_func=None):
        vulns = []
        exist_fatal_error = False
        exist_error_file = False
        exist_exception_file = False

        sqli_error_file = os.path.join(self.mysql_errors_folder, f"{candidate.coverage_id}.json")
        if os.path.isfile(sqli_error_file):
            #if sqli, don't catch return value.
            self.inspect_error_file(vulns,candidate, sqli_error_file,vuln_type=Vulnerability.SQLI,
                                                                     vuln_func=vuln_func)
            
        ce_error_file = os.path.join(self.shell_errors_folder, f"{candidate.coverage_id}.json")
        if os.path.isfile(ce_error_file):
            #if code execution, don't catch return value. 
            self.inspect_error_file(vulns,candidate, ce_error_file,vuln_type=Vulnerability.CODE_EXEC)

        error_file = os.path.join(self.error_files_folder, f"{candidate.coverage_id}.json")
        if os.path.isfile(error_file):
            exist_error_file = True
            #if error file, then catch since fatal error might be set by error['type]
            exist_fatal_error = self.inspect_error_file(vulns,candidate, error_file, 
                                                        throwable_type=ThrowableType.ERROR)

        exception_file = os.path.join(self.exception_files_folder, f"{candidate.coverage_id}.json")
        if os.path.isfile(exception_file):
            exist_exception_file = True
            #if exception file, don't catch return value
            self.inspect_error_file(vulns, candidate, exception_file,throwable_type=ThrowableType.EXCEPTION)

        zend_exec_status = self.get_zend_execution_status(candidate.api_call_status, exist_error_file, 
                                                          exist_exception_file, exist_fatal_error)
        return vulns, zend_exec_status
                                                              #* enforcing pass by keyword
    def inspect_error_file(self, vulns, candidate, error_file, *, vuln_type=None, throwable_type=None,vuln_func=None):
        fatal_error_existed = False
        try:
            with open(error_file, "r") as f:
                prev_error_log = None
                # line = 0                          #just to check that all json lines are read
                for jsonline in f:
                    jsonline = jsonline.strip()
                    # line += 1                     #just to check that all json lines are read               
                    if not jsonline:
                        continue
                    error_log = json.loads(jsonline) #check if fatal error.
                    if not fatal_error_existed and throwable_type == ThrowableType.ERROR:
                        fatal_error_existed = bool(error_log['type'] & FATAL_ERROR_FLAGS)

                    should_inspect = (      #check if error is already seen, skip for efficiency.
                        prev_error_log is None or
                        error_log['function_name'] != prev_error_log['function_name'] or
                        error_log['filename'] != prev_error_log['filename'] or
                        error_log['lineno'] != prev_error_log['lineno']
                    ) 
                    if should_inspect:
                        if vuln_type == Vulnerability.SQLI:
                            self.uncover_sqli_vuln_bug(vulns, error_log, candidate, vuln_func=vuln_func)
                        elif vuln_type == Vulnerability.CODE_EXEC:
                            self.uncover_codeexec_vuln_bug(vulns, error_log, candidate)
                        elif vuln_type is None:
                            self.generic_vuln_bug_identifier(vulns, error_log, candidate, vuln_func=vuln_func) 
                        prev_error_log = error_log
                return fatal_error_existed
        except Exception as e:
            print(f"Fails opening error file: {e}")
                    
    def uncover_sqli_vuln_bug(self, vulns, error_log, candidate, vuln_func=None):
        is_func_vuln = error_log['function_name'] in function_traces.SQLI_VULN_FUNCTIONS
        if 'error_no' in error_log:     #from error log, mysql error log, shell error log
            err_no = error_log['error_no'] 
        elif 'code' in error_log:
            err_no = error_log['code']      #from exception log
        is_payload_reflected = False
        is_payload_empty = False  
        for param in candidate.fuzz_params.keys():
            for k, v in candidate.fuzz_params[param].items():
                if len(v) > 0 and str(v) in error_log['query']:
                    is_payload_reflected = True #fuzz param is fully reflected/all fuzz param chars in query
                    break
                elif len(v) > 0:
                    is_payload_reflected = utils.is_payload_in_sink(v, error_log['query'])
                    break
                elif len(v) == 0:           #possible payload is an empty string
                    is_payload_empty = True #don't break because other payloads may reach sink, strengthen detection
            if(is_payload_reflected):
                break
        
        if is_func_vuln and err_no == 1064 and is_payload_reflected:
            report = self.create_report(error_log, SoftwareFlaw.VULNERABILITY, Vulnerability.SQLI)
        elif is_func_vuln and is_payload_empty and err_no == 1064: #possible payload is empty.
            report = self.create_report(error_log, SoftwareFlaw.VULNERABILITY, Vulnerability.SQLI)
        elif vuln_func and Key.FUNC_SANIT_REP in vuln_func and vuln_func.get(Key.FUNC_SANIT_REP) and err_no == 1064:
            #very weak check, better technique is to detransform input params using FUNC_SANIT_REP
            report = self.create_report(error_log, SoftwareFlaw.VULNERABILITY, Vulnerability.SQLI)
        else:
            report = self.create_report(error_log, SoftwareFlaw.BUG)
            #this is where the inspection of database is signaled to main fuzzer.
        vulns.append(report)
     

    def uncover_codeexec_vuln_bug(self, vulns, error_log, candidate):
        is_func_vuln = error_log['function_name'] in function_traces.CODE_EXEC_VULN_FUNCTIONS
        is_payload_reflected = False
        for param in candidate.fuzz_params.keys():
            for k, v in candidate.fuzz_params[param].items():
                if str(v) in error_log['command']:
                    is_payload_reflected = True
                    break
                else:
                    is_payload_reflected = utils.is_payload_in_sink(v, error_log['command'])
                    break
            if is_payload_reflected:
                break
        
        if(is_func_vuln and is_payload_reflected):
            report = self.create_report(error_log, SoftwareFlaw.VULNERABILITY, Vulnerability.CODE_EXEC)
        else:
            report = self.create_report(error_log, SoftwareFlaw.BUG)
        vulns.append(report)

    def generic_vuln_bug_identifier(self, vulns, error_log, candidate, vuln_func=None):
        for report in vulns:                    # check if the report already exists for error log, e.g sqli error also produced error log
            if report[Key.FILENAME] == error_log["filename"] and report[Key.LINENO] == error_log["lineno"]:
                return
            
        is_func_vuln = False
        is_payload_reflected = False #payload is fully reflected in the error message
        gen_fname = (error_log['function_name'] == "\u0002")
        fname_letters = any(c.isalpha() for c in error_log['function_name'])
        # function_name = error_log['function_name']
        if(gen_fname or not fname_letters) and 'error' in error_log:     #if the function name is NOT correctly produced.
            match = re.search(r"\b(\w+)\s*\(", error_log["error"])
            if match and match.group(1):
                # function_name = match.group(1)
                error_log['function_name'] = match.group(1)
        
        # if function_name in function_traces.PATHTRAVS_VULN_FUNCTIONS:
        if error_log['function_name'] in function_traces.PATHTRAVS_VULN_FUNCTIONS:
            is_func_vuln = True
            vuln_type = Vulnerability.PATHTRAVS
        elif error_log['function_name'] in function_traces.UNSERIALIZE_VULN_FUNCTIONS:
            is_func_vuln = True
            vuln_type = Vulnerability.UNSERIALIZE
        elif error_log['function_name'] in function_traces.XXE_VULN_FUNCTIONS:
            is_func_vuln = True
            vuln_type = Vulnerability.XXE
        else:
            report = self.create_report(error_log, SoftwareFlaw.BUG)
            vulns.append(report)
            return 

        for param in candidate.fuzz_params.keys():
            for k, v in candidate.fuzz_params[param].items():
                #handle error-report
                #Currently cannot handle (base64_encode|else)encoded fuzzed parameters that goes to
                #a serie of sanitation
                if 'error' in error_log and str(v) in error_log['error']:
                    is_payload_reflected = True
                    break
                elif 'error' in error_log and str(v) not in error_log['error']:
                    is_payload_reflected = utils.is_payload_in_sink(v, error_log['error'])
                    break
                #handle exception-report
                elif 'message'in error_log and str(v) in error_log['message']:
                    is_payload_reflected = True
                    break
                elif 'message' in error_log and str(v) not in error_log['message']:
                    is_payload_reflected = utils.is_payload_in_sink(v, error_log['message'])
                    break
            if(is_payload_reflected):
                break
        
        if(is_func_vuln and is_payload_reflected):
            report = self.create_report(error_log, SoftwareFlaw.VULNERABILITY, vuln_type)
        elif vuln_func and Key.FUNC_SANIT_REP in vuln_func and vuln_func.get(Key.FUNC_SANIT_REP):
            #very weak check, better technique is to detransform input params using FUNC_SANIT_REP
            report = self.create_report(error_log, SoftwareFlaw.VULNERABILITY, vuln_type)
        else:
            report = self.create_report(error_log, SoftwareFlaw.BUG) #when error happens and no vuln func, assumed as BUG
        vulns.append(report)

    #XSS must check 0xdeadbeef since previous research wfuzz code is used
    def uncover_xss_vuln(self, c, vuln_func=None, param_key_tup=None, flags=None):
        report = {}
        is_vuln = False
        
        is_vuln = self.xss_vulnchecker.check(c) #cal xss check func from previous research: webfuzz
        if is_vuln:
            report = {  Key.FLAW_TYPE : SoftwareFlaw.VULNERABILITY,
                        Key.VULN_TYPE : Vulnerability.XSS,
                        # 'payload' : v, 
                        Key.HTTP_TARGET: c.http_target,
                        Key.HTTP_METHOD: c.http_method}
            if vuln_func and param_key_tup:
                if utils.match_fuzz_params_to_function_param(c.fuzz_params, vuln_func):
                    report[Key.FUNCTION_NAME] = vuln_func[Key.FUNCTION_NAME]
                    report[Key.FILENAME] = vuln_func[Key.FILENAME]
                    report[Key.LINENO] = vuln_func[Key.LINENO]
        return report
        
    
    def uncover_open_redirect_vuln(self, c):
        if self.open_redirect_vulnchecker(c):
            report = {  Key.FLAW_TYPE : SoftwareFlaw.VULNERABILITY,
                        Key.VULN_TYPE : Vulnerability.XSS,
                        Key.HTTP_TARGET: c.http_target,
                        Key.HTTP_METHOD: c.http_method}
            if report:
                return report
        return None

    def create_report(self, error_log, flaw_type, vuln_type=None):
        report = {Key.FLAW_TYPE: flaw_type,
                  Key.FUNCTION_NAME: error_log['function_name'],
                  Key.FILENAME: error_log['filename'],
                  Key.LINENO: error_log['lineno']}
                  
        if(vuln_type):
            report[Key.VULN_TYPE] = vuln_type
            if(vuln_type == Vulnerability.SQLI):
                report[Key.QUERY] = error_log['query']
            elif(vuln_type == Vulnerability.CODE_EXEC):
                report[Key.COMMAND] = error_log['command']
           
            if 'error' in error_log:
                report[Key.ERROR] = error_log['error']
            if 'message' in error_log:
                report[Key.ERROR] = error_log['message']
            if 'error_no' in error_log:     #from error log, mysql error log, shell error log
                report[Key.ERROR_NO] = error_log['error_no'] #mysql error log
            if 'code' in error_log:
                report[Key.ERROR_NO] = error_log['code']     #exception log
            if 'error_no' not in error_log or 'error_no' not in error_log:
                report[Key.ERROR_NO] = 0  
        else: #BUG
            if 'error' in error_log:
                report[Key.ERROR] = error_log['error']
            if 'message' in error_log:
                report[Key.ERROR] = error_log['message']
            if 'error_no' in error_log:     #from error log, mysql error log, shell error log
                report[Key.ERROR_NO] = error_log['error_no'] #mysql error log
            if 'code' in error_log:
                report[Key.ERROR_NO] = error_log['code']     #exception log
            if 'error_no' not in error_log or 'error_no' not in error_log:
                report[Key.ERROR_NO] = 0  
        report[Key.FLAW_CHECKER]= FlawChecker.ERROR_BASED_CHECKER
        return report

    def is_fatal_error(self, error_type):
        return bool(error_type & FATAL_ERROR_FLAGS)

    def get_zend_execution_status(self, api_call_status, exist_error_file=False, exist_exception_file=False, exist_fatal_error=False):
        """
        Check if the script was terminated early due to an error or exception or die/exit function.
        """
        exec_status = 0
        exist_die_exit = bool(api_call_status & APICallTraceStatus.EXIST_DIE_EXIT_FUNCTION)
        
        if exist_fatal_error:
            exec_status |= ZendExecStatus.FATAL_ERROR_TERMINATION
        elif exist_die_exit:
            exec_status |= ZendExecStatus.DIE_EXIT_TERMINATION
        elif exist_error_file and not exist_fatal_error:
            exec_status |= ZendExecStatus.CONTINUE_WITH_NON_FATAL_ERROR
        elif exist_exception_file and not exist_fatal_error:
            exec_status |= ZendExecStatus.CONTINUE_WITH_EXCEPTION
        else:
            exec_status |= ZendExecStatus.CONTINUE_NORMAL
        return exec_status
       
    def logic_based_vuln_check(self, c, logic_vuln_type, vuln_func=None, param_key_tup=None, flags=None):
        vulns = []
        if logic_vuln_type & LogicVulnType.XSS:
            vuln = self.uncover_xss_vuln(c, vuln_func, param_key_tup, flags)
            if vuln:
                vulns.append(vuln)
        elif logic_vuln_type & LogicVulnType.OPEN_REDIRECT:
            vuln = self.uncover_open_redirect_vuln(c)
            if vuln:
                vulns.append(vuln)
        return vulns
    
    def sql_function_based_vuln_check(self, c, vuln_func, malice_param_key): #malice_param_key is tuple keys for fuzz_params receiving malice dict payld
        vulns = []
        trace = c.function_trace
        if utils.is_function_in_list(vuln_func,trace):
            q_retval = vuln_func[Key.RETURN_VALUE]
            if q_retval and self.sql_malicious_payload_succeeded(c, vuln_func, malice_param_key):     #correct use utils.is_sql_malicious_payload_tamed, only called when func retval is true
                report = self.report_function_based_vuln(vuln_func, c, Vulnerability.SQLI)  #
                vulns.append(report)
        return vulns
    
    def sql_malicious_payload_succeeded(self, c, vuln_func, malice_param_key):
        payload = c.fuzz_params[malice_param_key[0]][malice_param_key[1]]
        query = vuln_func[Key.QUERY]
        # if payload in query:    #should be verified to check the payload works in mysqli shell
        #     return False
        params_in_sink = vuln_func[Key.PARAMS_IN_SINK]
        quoted = params_in_sink[malice_param_key][Key.QUOTED]   #whether the malice param is quoted in the sink
        if quoted:  #if quoted, then check if real_escape_string or PDO::quote is used to sanitize the payload
            for sf in c.sanit_functions:
                if sf[Key.FUNCTION_NAME] in function_traces.SQL_SFUNCTIONS:
                    input = sf[Key.STRING]
                    sanit_retval = sf[Key.RETURN_VALUE]
                    if utils.is_payload_in_sink(sanit_retval, query) and utils.is_payload_in_sink(payload, input):
                        return False
        flag = utils.is_sql_payload_turn_to_logic(payload, query)
        return flag
    
    def generic_function_based_vuln_check(self,c, vuln_func, malice_param_key, func_vulnerability):
        payload = c.fuzz_params[malice_param_key[0]][malice_param_key[1]] #Must check if payload lands in sink
        vulns = []
        report = None
        trace = c.function_trace
        if func_vulnerability == Vulnerability.CODE_EXEC:
            sink = vuln_func[Key.COMMAND]
        elif func_vulnerability == Vulnerability.PATHTRAVS:
            sink = vuln_func[Key.PATH]
        elif func_vulnerability == Vulnerability.UNSERIALIZE:
            sink = vuln_func[Key.SERIALIZED_STRING]
        elif func_vulnerability == Vulnerability.XXE:
            sink = vuln_func[Key.XML_PAYLOAD]
        
        if Key.FUNC_SANIT_REP in vuln_func and vuln_func.get(Key.FUNC_SANIT_REP):
            sanit_rep = vuln_func[Key.FUNC_SANIT_REP]
            sanit_seq = sanit_rep[malice_param_key][0]
            sink = function_traces.generate_sanitation_aware_string(sanit_seq,sink)
        
        if utils.is_function_in_list(vuln_func,trace):
            retval = vuln_func[Key.RETURN_VALUE]
            if retval and utils.is_payload_in_sink(payload,sink):
                if (vuln_func[Key.FUNCTION_NAME] in function_traces.CODE_EXEC_VULN_FUNCTIONS and 
                    vuln_func[Key.FUNCTION_NAME] not in ['assert','eval']):  #other code exec functions
                    if Key.IS_ERROR in vuln_func and not vuln_func[Key.IS_ERROR]:
                        report = self.report_function_based_vuln(vuln_func, c, func_vulnerability)
                elif(vuln_func[Key.FUNCTION_NAME] in function_traces.PATHTRAVS_VULN_FUNCTIONS and 
                     vuln_func[Key.FUNCTION_NAME] not in ['include', 'include_once', 'require', 'require_once']):  #other pathtraversal functions
                    if vuln_func[Key.RETURN_VALUE]:
                        report = self.report_function_based_vuln(vuln_func, c, func_vulnerability)
                elif(vuln_func[Key.FUNCTION_NAME] in function_traces.UNSERIALIZE_VULN_FUNCTIONS):
                    if  vuln_func[Key.RETURN_VALUE]:
                        report = self.report_function_based_vuln(vuln_func, c, func_vulnerability)
                elif(vuln_func[Key.FUNCTION_NAME] in function_traces.XXE_VULN_FUNCTIONS):
                    if vuln_func[Key.RETURN_VALUE]:
                        report = self.report_function_based_vuln(vuln_func, c, func_vulnerability)
                
                if report:
                    vulns.append(report)
        return vulns

    def report_function_based_vuln(self, vuln_func, c, vuln_type):
        report = {Key.FLAW_TYPE: SoftwareFlaw.VULNERABILITY,
                  Key.VULN_TYPE: vuln_type,
                  Key.FUNCTION_NAME: vuln_func[Key.FUNCTION_NAME],
                  Key.FILENAME: vuln_func[Key.FILENAME],
                  Key.LINENO: vuln_func[Key.LINENO]}
        report[Key.FLAW_CHECKER] = FlawChecker.FUNCTION_BASED_CHECKER
        return report

                
                    
   
   