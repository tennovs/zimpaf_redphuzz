import os
import random
import json
import gzip
import hashlib
from difflib import SequenceMatcher
import sqlglot
# from sqlglot.expressions import Literal, Placeholder, Null, Boolean, Insert, Select, Update, Delete, Column, Schema 
import re
from sqlparse.tokens import Token as T
import string
import sys
import copy
from datetime import datetime, timedelta

def fuzz_open(path, mode="r"):
    if os.environ["FUZZER_COMPRESS"] == "1":
        return gzip.open(path, mode + "t")
    else:
        return open(path, mode)

def string_is_number(string):
    # checks if string is a number
    try:
        int(string)
        return True
    except ValueError:
        return False


def sort_by_sublist_length(list_of_lists):
    return sorted(list_of_lists, key=lambda x: (len(x), x[0]["name"], x[0]['value']), reverse=True)


def strip_quotes(strings):
    return [string.strip('"\'') for string in strings]


def get_file_path(file_name):
    return f"{os.path.dirname(os.path.realpath(__file__))}{file_name}"


def get_path_growth(paths_previous, paths_current):
    return len(paths_current) - len(paths_previous)


def coverage_report_has_functions(coverage_report_for_file):
    return "functions" in coverage_report_for_file and type(coverage_report_for_file["functions"]) == dict


def coverage_report_has_lines(coverage_report_for_file):
    return "lines" in coverage_report_for_file and type(coverage_report_for_file["lines"]) == dict


def get_executed_lines(coverage_report, file_name):
    for x in coverage_report[file_name]["lines"].keys():
        if coverage_report[file_name]["lines"][x] > 0:
            yield x

def stringify_hit_or_line(file, path):
    try:
        return f'{file}::::{"_".join([str(x) for x in path["path"]])}'
    except:
        return f'{file}::::{"_".join([str(x) for x in path["lines"]])}'


def stringify_hit_paths(hit_paths):
    return [
        stringify_hit_or_line(file, hit)
        for path in hit_paths
        for file in path
        for hit in path[file]
    ]

def lines_count_dict(hit_paths):
    d = {}
    for path in hit_paths:
        for file in path:
            for hit in path[file]:
                for hp in hit['path']:
                    key = f"{file}:{hp}"
                    if not key in d:
                        d[key] = 1
                    else:
                        d[key] += 1
    return d


def add_paths(paths, new_paths):
    return paths + [p for p in new_paths if p not in paths]


def get_executed_paths(coverage_report, file_name, function):
    for x in coverage_report[file_name]["functions"][function]["paths"]:
        if x["hit"] > 0:
            yield x


def extract_hit_paths(coverage_report):
    hit_paths = []
    for file in coverage_report.keys():
        if "__fuzzer__" in file:
            continue
        if file == "__time__":
            continue

        # XDEBUG coverage
        if coverage_report_has_functions(coverage_report[file]):
            for function in coverage_report[file]["functions"]:
                paths = list(get_executed_paths(coverage_report, file, function))
                hit_paths.append({file: paths})
        elif coverage_report_has_lines(coverage_report[file]):
            lines = list(get_executed_lines(coverage_report, file))
            paths = [{"lines": [int(x) for x in lines], "hit": 1}]
            hit_paths.append({file: paths})

        # PCOV coverage
        else:
            #       x = (line_no, hit_info) -> (49, -1|1) -> We only want hit lines with 1
            lines = sorted(map(lambda y: y[0], filter(lambda x: x[1] > 0, coverage_report[file].items())))
            paths = [{"lines": [int(x) for x in lines], "hit":1 }]
            hit_paths.append({file: paths})

    return hit_paths

def sort_by_length(list_of_dicts):
    return sorted(list_of_dicts, key=lambda x: len(x))

def read_har_file(file_path):
    with fuzz_open(file_path, "r") as f:
        return json.load(f)


def parse_requests(data):
    requests = data["log"]["entries"]
    request_info = []
    for request in requests:
        method = request['request']['method']
        cookies = request["request"]["cookies"]
        query_string = request["request"]["queryString"]
        headers = request["request"]["headers"]
        try:
            payload = request["request"]["postData"]["text"]
        except KeyError:
            payload = []
        try:
            form_data = request["request"]["postData"]["params"]
        except KeyError:
            form_data = []
        info = {
            "url": request["request"]["url"],
            'method': method,
            "cookies": cookies,
            "query_string": query_string,
            "headers": headers,
            "payload": payload,
            "form_data": form_data,
        }
        request_info.append(info)
    return request_info


def filter_requests_by_domain(requests, domain):
    return [
        request
        for request in requests
        if domain in request["url"]
    ]


def extract_input_vectors_from_har(file_path, domain=None):
    data = read_har_file(file_path)
    requests = parse_requests(data)
    if domain:
        return filter_requests_by_domain(requests, domain)
    else:
        return requests
 


###These functions are added and written by tennov
#used by zimpaf extension to process code coverage reports that is based on conditional statements and outcome

from libzimpaf.constants import Key, MatchingPattern, Vulnerability
import pymysql
import sqlglot
from sqlglot.expressions import (
    Column, Literal, EQ, NEQ, GT, LT, GTE, LTE, In, Between, Like, Table, Placeholder, Literal, Placeholder, 
    Null, Boolean, Insert, Select, Update, Delete, Column, Schema
)
from sqlglot import exp
import rstr

#Need to add guard for file that is not produced by zimpaf.
def get_path_list(coverage_report):
    path_list = []
    for entry in coverage_report:
        file, branches = entry.split("::::")
        branch_condition = list(branches.split("_"))
        path_list.append({file:branch_condition})
    return path_list

def merge_paths_from_same_file(path_list):
    combined_data = {} # Initialize a regular dictionary
    for entry in path_list:
        for file, branches in entry.items():
            if file in combined_data:
                combined_data[file].extend(branches)
            else:
                combined_data[file] = branches[:]
    # print("Combined file with branches:")
    # print(combined_data)
    return combined_data

def get_number_of_branches(path_list):
    combined_data = merge_paths_from_same_file(path_list)
    num_branches = sum(len(set(branches)) for branches in combined_data.values())
    # print(f"Total number of branches: {num_branches}")
    return num_branches

def get_new_path_list(child_path_list, parent_path_list):
    child_combined_data = merge_paths_from_same_file(child_path_list)
    parent_combined_data = merge_paths_from_same_file(parent_path_list)
    new_path_list = []
    for file, branches in child_combined_data.items():
        if file in parent_combined_data:
            new_branches = set(branches) - set(parent_combined_data[file])
            if new_branches:
                new_path_list.append({file:list(new_branches)})
        else:
            new_path_list.append({file:list(set(branches))})
    #print("New paths not in parent:")
    return new_path_list

def merge_child_and_parent_paths(child_path_list, parent_path_list):
    child_combined_data = merge_paths_from_same_file(child_path_list)
    parent_combined_data = merge_paths_from_same_file(parent_path_list)
    merged_paths = []
    for file, branches in child_combined_data.items():
        if file in parent_combined_data:
            merged_branches = list(set(branches) | set(parent_combined_data[file]))
            merged_paths.append({file: merged_branches})
        else:
            merged_paths.append({file: list(set(branches))})
    return merged_paths

def stringify_paths_list(paths_list):
    stringified_paths = []
    for entry in paths_list:
        for file, branches in entry.items():
            branch_string = "_".join(branches)
            stringified_paths.append(f"{file}::::{branch_string}")
    # with open("stringified_combined_paths.txt", "w") as f:
    #     f.write("\n".join(stringified_paths))
    return stringified_paths

def get_path_hash(coverage_report):
    path_string = json.dumps(coverage_report)  # no sort_keys needed for a list
    path_hash = hashlib.sha256(path_string.encode()).hexdigest()
    # print(path_hash)
    return path_hash

def get_vuln_hash(vuln, c):
    # Select only the fields that make the vulnerability distinct
    values = [str(vuln[k]) for k in vuln.keys() if k in [Key.FLAW_TYPE, Key.FUNCTION_NAME, Key.VULN_TYPE, 
                                                         Key.ERROR_NO, Key.FILENAME, Key.LINENO, Key.HTTP_TARGET, 
                                                         Key.HTTP_METHOD]]
    '''
    Multiple vulnerabilities can happen even to a single candidate/endpoint with different paths
    Therefore need to differentiate from which path is the vulnerability or bug
    '''
    values.append(c.path_hash) 
    concat_str = ''.join(values)
    return hashlib.sha256(concat_str.encode('utf-8')).hexdigest()

def load_json_file(path, mode='r'):
    if not os.path.exists(path):
        print(f"File {path} does not exist.")
        return None
    try:
        with open(path,mode) as f:
            return json.load(f)
    except Exception as e:
        with open(path, mode, encoding='latin-1') as f:
            return json.load(f)

def get_function_hash(func):
    func_string = func[Key.FUNCTION_NAME] + func[Key.FILENAME] + str(func[Key.LINENO])
    func_hash = hashlib.sha256(func_string.encode()).hexdigest()
    return func_hash

import hashlib

def get_function_params_hash(func, fixed_params, fuzz_params):
    func_string = (func[Key.FUNCTION_NAME] + func[Key.FILENAME] + str(func[Key.LINENO])
    )
    fixed_parts = []
    fuzz_parts = []

    for req_part, params in fixed_params.items():
        for k, v in params.items():
            fixed_parts.append(str(v))

    for req_part, params in fuzz_params.items():
        for k, v in params.items():
            fuzz_parts.append(str(v))

    fixed_string = "".join(fixed_parts)
    fuzz_string = "".join(fuzz_parts)
    whole_string = f"{func_string}_{fixed_string}_{fuzz_string}"
    return hashlib.sha256(whole_string.encode()).hexdigest()


def get_and_classify_query_params(query):
    """
    Returns two lists of parameter values in a SQL query:
    1. quoted_params: string literals (inside quotes)
    2. unquoted_params: numbers, NULL, booleans
    Works for INSERT, SELECT, UPDATE, DELETE across common dialects:
    MySQL, PostgreSQL, T-SQL, SQLite.
    """
    # Simple heuristic for dialect
    if "`" in query:
        dialect = "mysql"
    elif '"' in query:
        dialect = "postgres"
    elif "[" in query and "]" in query:
        dialect = "tsql"
    else:
        dialect = None  # fallback / ANSI

    try:
        exprs = sqlglot.parse(query, read=dialect)
    except Exception as e:
        print(f"SQLGlot parse error: {e}")
        return None, None, None

    
    quoted_params = []
    numeric_params = []
    unquoted_params = []

    for expr in exprs:
    # For each expr, traverse entire AST recursively
        for node in expr.walk():
            # String literals
            if isinstance(node, exp.Literal):
                if node.is_string:
                    quoted_params.append(node.this)
                else:
                    # Numeric literals or unquoted constants
                    try:
                        numeric_params.append(int(node.this))
                        unquoted_params.append(int(node.this))
                    except ValueError:
                        try:
                            numeric_params.append(float(node.this))
                            unquoted_params.append(float(node.this))
                        except ValueError:
                            pass

            # NULL values
            elif isinstance(node, exp.Null):
                unquoted_params.append("NULL")
            # Boolean literals
            elif isinstance(node, exp.Boolean):
                unquoted_params.append(str(node.this).upper())
            # Placeholders (?, :name, $1) are ignored
            elif isinstance(node, exp.Placeholder):
                continue
    return quoted_params, numeric_params, unquoted_params

def get_query_params_quoted(query):
    if "`" in query:
        dialect = "mysql"
    elif '"' in query:
        dialect = "postgres"
    elif "[" in query and "]" in query:
        dialect = "tsql"
    else:
        dialect = None  #fallback / ANSI

    try:
        exprs = sqlglot.parse(query, read=dialect)
    except Exception as e:
        #show parse error for debugging
        print(f"SQLGlot parse error: {e}")
        return None

    quoted_params = []
    for expr in exprs:  # handles multiple queries
        for node in expr.walk():
            if isinstance(node, exp.Literal) and node.is_string:
                quoted_params.append(node.this)
    return quoted_params


def get_query_params_numeric(query):
    if "`" in query:
        dialect = "mysql"
    elif '"' in query:
        dialect = "postgres"
    elif "[" in query and "]" in query:
        dialect = "tsql"
    else:
        dialect = None  #fallback / ANSI

    try:
        exprs = sqlglot.parse(query, read=dialect)
    except Exception as e:
        print(f"SQLGlot parse error: {e}")
        return None

    numeric_params = []
    for expr in exprs:  # handles multiple queries
        for node in expr.walk():
            if isinstance(node, exp.Literal):
                try:
                    numeric_params.append(int(node.this))
                except ValueError:
                    try:
                        numeric_params.append(float(node.this))
                    except ValueError:
                        pass
    return numeric_params

def get_query_params_unquoted(query):
    if "`" in query:
        dialect = "mysql"
    elif '"' in query:
        dialect = "postgres"
    elif "[" in query and "]" in query:
        dialect = "tsql"
    else:
        dialect = None  #fallback / ANSI

    try:
        exprs = sqlglot.parse(query, read=dialect)
    except Exception as e:
        print(f"SQLGlot parse error: {e}")
        return None

    unquoted_params = []
    for expr in exprs:  # handles multiple queries
        for node in expr.walk():
            if isinstance(node, exp.Literal):
                try:
                    unquoted_params.append(int(node.this))
                except ValueError:
                    try:
                        unquoted_params.append(float(node.this))
                    except ValueError:
                        pass
            elif isinstance(node, exp.Null):
                unquoted_params.append("NULL")
            elif isinstance(node, exp.Boolean):
                unquoted_params.append(str(node.this).upper())
    return unquoted_params

def get_query_params_placeholders(query):
    if "`" in query:
        dialect = "mysql"
    elif '"' in query:
        dialect = "postgres"
    elif "[" in query and "]" in query:
        dialect = "tsql"
    else:
        dialect = None  #fallback / ANSI

    try:
        exprs = sqlglot.parse(query, read=dialect)
    except Exception as e:
        print(f"SQLGlot parse error: {e}")
        return None

    params_placeholders = []
    for expr in exprs:  # handles multiple queries
        for node in expr.walk():
            if isinstance(node, exp.Placeholder):
                params_placeholders.append(node.name)
    return params_placeholders


def is_payload_in_sink(needle, haystack):  #haystack is where to search, needle is what string to search
    if not haystack or not needle:
        return False
    
    haystack_str = haystack if isinstance(haystack, str) else str(haystack)
    needle_str = needle if isinstance(needle, str) else str(needle)
    
    if needle_str in haystack_str:
        return True

    hay_words = haystack_str.split()                               
    ned_words = needle_str.split()
    if len(hay_words) == 0 or len(ned_words) == 0:
        return False
    len_n_words = 0         #excluding white spaces
    len_matched_word = 0

    # matched_sequence = []
    for n_word in ned_words:
        len_n_words += len(n_word)
        for h_word in hay_words:
            ratio = SequenceMatcher(None, h_word, n_word).ratio()
            if ratio > 0.6:
                # matched_sequence.append(word)
                len_matched_word += len(n_word)
                break
    matched_chars_ratio = len_matched_word / len_n_words
    if matched_chars_ratio > 0.5:
        return True
    else:
        return False
    

def is_insert_query(query):
    if "`" in query:
        dialect = "mysql"
    elif '"' in query:
        dialect = "postgres"
    elif "[" in query and "]" in query:
        dialect = "tsql"
    else:
        dialect = None  #fallback / ANSI

    try:
        exprs = sqlglot.parse(query, read=dialect)
    except Exception as e:
        print(f"The query is corrupted. Error: {e}")
        return None
    for expr in exprs:  # handles multiple queries
        # if expr.__class__.__name__ == "Insert":
        if isinstance(expr, exp.Insert):
            return True
    return False

def parse_tables_columns_from_query(query):
    if "`" in query:
        dialect = "mysql"
    elif '"' in query:
        dialect = "postgres"
    elif "[" in query and "]" in query:
        dialect = "tsql"
    else:
        dialect = None  #fallback / ANSI

    try:
        exprs = sqlglot.parse(query, read=dialect)
    except Exception as e:
        print(f"SQLglot error, cannot parse the bad-formed query. Error: {e}")
        return None
    
    results = []
    for expr in exprs:  # handles multiple queries
        if expr.__class__.__name__ == "Insert":
            results.extend(parse_tables_columns_from_insert_query(expr))  
        elif expr.__class__.__name__ == "Select":
            results.extend(parse_tables_columns_from_select_query(expr))
        elif expr.__class__.__name__ in ["Update", "Delete"]:
            results.extend(parse_tables_columns_from_update_delete_query(expr))
    return results

def parse_tables_columns_from_select_query(ast_or_query_string):
    if isinstance(ast_or_query_string, str):
        try:
            expr = sqlglot.parse_one(ast_or_query_string)
        except Exception as e:
            print(f"SQLglot error, cannot parse the bad-formed query. Error: {e}")
            return None
    else:
        expr = ast_or_query_string

    if not isinstance(expr, Select):
        return None

    COMPARISON_TYPES = (EQ, NEQ, GT, LT, GTE, LTE, In, Between, Like)
    
    targets = []
    is_quoted = False
    alias_map = {t.alias or t.name: t.name for t in expr.find_all(Table)}

    first_table = next(expr.find_all(Table), None)
    default_table = first_table.name if first_table else None

    for cmp in expr.find_all(COMPARISON_TYPES):
        #get only columns' name that receives user input, meaning use cmp oper and literal
        if not list(cmp.find_all(Literal, Placeholder)):
            continue
        for col in cmp.find_all(Column):
            #if alias is used, resolve it to the actual table name
            table_name = alias_map.get(col.table) if col.table else default_table
            rhs_value = None
            if isinstance(cmp, (EQ, NEQ, GT, LT, GTE, LTE, Like)):
                operator = cmp.key.upper()
                # print(f"operator = {operator}")
                if isinstance(cmp.right, Literal):
                    rhs_value = cmp.right.this
                    if cmp.right.is_string:
                        is_quoted = True
                elif isinstance(cmp.right, Placeholder):
                    rhs_value = f":{cmp.right.name}"
            #IN / NOT IN
            elif isinstance(cmp, In):
                rhs_value = []
                operator = cmp.key.upper()
                for val in cmp.expressions:
                    if isinstance(val, Literal):
                        rhs_value.append(val.this)
                        if getattr(val, 'is_string', False):
                        # if cmp.right.is_string:
                            is_quoted = True
                    elif isinstance(val, Placeholder):
                        rhs_value.append(f":{val.name}")

            #BETWEEN / NOT BETWEEN
            elif isinstance(cmp, Between):
                rhs_value = []
                for val in [cmp.args.get("low"), cmp.args.get("high")]:
                    if isinstance(val, Literal):
                        rhs_value.append(val.this)
                        if cmp.right.is_string:
                            is_quoted = True
                    elif isinstance(val, Placeholder):
                        rhs_value.append(f":{val.name}")

            targets.append({
                Key.TABLE: table_name,
                Key.COLUMN: col.name,
                Key.VALUE: rhs_value,
                Key.QUOTED: is_quoted
            })
    return targets

def parse_tables_columns_from_insert_query(ast_or_query_string):
    if isinstance(ast_or_query_string, str):
        try:
            expr = sqlglot.parse_one(ast_or_query_string)
        except Exception as e:
            print(f"SQLglot error, cannot parse the bad-formed query. Error: {e}")
            return None
    else:
        expr = ast_or_query_string

    if not isinstance(expr, exp.Insert):
        return None

    table_name = expr.find(exp.Table).name if expr.find(exp.Table) else None
    columns = [col.name for col in expr.find(exp.Schema).expressions] if expr.find(exp.Schema) else []
    targets = []

    source = expr.expression

    #case 1: INSERT ... VALUES ...
    if source and isinstance(source, exp.Values):
        for row in source.expressions:
            for col, val in zip(columns, row.expressions):
                if isinstance(val, exp.Literal):
                    rhs_value = val.this
                    is_quoted = val.is_string
                else:
                    rhs_value = val.sql()
                    is_quoted = False

                targets.append({
                    Key.TABLE: table_name,
                    Key.COLUMN: col,
                    Key.VALUE: rhs_value,
                    Key.QUOTED: is_quoted
                })

    #case 2: INSERT ... SELECT ...
    elif source and isinstance(source, exp.Select):
        #SELECT columns match the INSERT target column list
        for col, val in zip(columns, source.expressions):
            if isinstance(val, exp.Literal):
                rhs_value = val.this
                is_quoted = val.is_string
            else:
                # rhs_value = val.sql()
                # rhs_value = val.name if isinstance(val, exp.Column) else val.sql()
                # rhs_value = None
                rhs_value = ''
                is_quoted = False

            targets.append({
                Key.TABLE: table_name,
                Key.COLUMN: col,
                Key.VALUE: rhs_value,
                Key.QUOTED: is_quoted
            })
    return targets

def parse_tables_columns_from_update_delete_query(ast_or_query_string):
    if isinstance(ast_or_query_string, str):
        try:
            expr = sqlglot.parse_one(ast_or_query_string)
        except Exception as e:
            print(f"SQLglot error, cannot parse the bad-formed query. Error: {e}")
            return None
    else:
        expr = ast_or_query_string

    if not isinstance(expr, Update) and not isinstance(expr, Delete):
        return None

    targets = []
    alias_map = {t.alias or t.name: t.name for t in expr.find_all(Table)}

    table = expr.this.name if expr.this else None
    default_table = table

    #handle SET and DELETE assignments
    for assignment in expr.expressions:  # each is EQ(col, val)
        if isinstance(assignment, exp.EQ):
            col = assignment.this
            rhs = assignment.expression

            if isinstance(rhs, exp.Literal):
                rhs_value = rhs.this
                is_quoted = rhs.is_string
            else:
                rhs_value = rhs.sql()
                is_quoted = False

            targets.append({
                "table": table,
                "column": col.name,
                "value": rhs_value,
                "quoted": is_quoted
            })

    #handle WHERE clause conditions iteratively
    if expr.where:
        expr_where = expr.args.get("where")
        COMPARISON_TYPES = (exp.EQ, exp.NEQ, exp.GT, exp.LT, exp.GTE, exp.LTE, exp.In, exp.Between, exp.Like)
        for cmp in expr_where.this.find_all(COMPARISON_TYPES):
            for col in cmp.find_all(exp.Column):
                table_name = alias_map.get(col.table) if col.table else default_table
                rhs_value = None
                is_quoted = False

                if isinstance(cmp, (exp.EQ, exp.NEQ, exp.GT, exp.LT, exp.GTE, exp.LTE, exp.Like)):
                    if isinstance(cmp.right, exp.Literal):
                        rhs_value = cmp.right.this
                        is_quoted = cmp.right.is_string
                    else:
                        rhs_value = cmp.right.sql()
                elif isinstance(cmp, exp.In):
                    rhs_value = [v.this if isinstance(v, exp.Literal) else v.sql()
                                 for v in cmp.expressions]
                    is_quoted = any(isinstance(v, exp.Literal) and v.is_string for v in cmp.expressions)
                elif isinstance(cmp, exp.Between):
                    vals = [cmp.args.get("low"), cmp.args.get("high")]
                    rhs_value = [v.this if isinstance(v, exp.Literal) else v.sql()
                                 for v in vals]
                    is_quoted = any(isinstance(v, exp.Literal) and v.is_string for v in vals)

                targets.append({
                    "table": table_name,
                    "column": col.name,
                    "value": rhs_value,
                    "quoted": is_quoted
                })
    return targets

def get_tables_cols_desc_from_db(tables_set, host, user, passwd, db):
    try:
        conn = pymysql.connect(host=host, user=user, password=passwd, database=db)
        tables_desc_dict = {tname: describe_table(conn, tname) for tname in tables_set}
        conn.close()
        return tables_desc_dict
    except Exception as e:
        print(f"\033[1;34mERROR CONNECTING TO DATABASE !!! : {e}\033[0m")
        return None
    
def describe_table(conn, table):
    TABLE_GUARD = re.compile(r"^[A-Za-z0-9_]+$") #avoid
    if not TABLE_GUARD.match(table):
        print(f"\033[1;34mINVALID TABLE NAME: {table} !!!\033[0m")
        return None
    try:
        cur = conn.cursor()
        cur.execute(f"DESCRIBE {table}")
        result = cur.fetchall()  # This is a list of tuples
        cur.close()
        return result
    except Exception as e:
        print(f"\033[1;34mERROR DECRIBING TABLE {table} !!!: {e}\033[0m")
        return []

def match_fuzz_params_to_function_param(fuzz_params, f_param): #fuzz_params is a collection of fuzzed parameters,
    matched_counts = 0                                         #f_param is the sink, function parameter of interest
    params_in_sink = {}                                        #retrieved from function trace
    for param in fuzz_params.keys():
        for k, v in fuzz_params[param].items():
            if is_payload_in_sink(v, f_param):
                matched_counts+=1
                params_in_sink[(param,k)] = True
    return params_in_sink

def generate_integer_string(n=5):
    if n < 1:
        raise ValueError("k must be at least 1")
    #first digit must be 1-9
    first = random.choice("123456789")
    #remaining digits
    rest = ''.join(random.choices(string.digits, k=n-1))
    return first + rest

def generate_type_conformant(data_type=None):
    #identifier for numeric data type
    numerics = ['int', 'dec', 'float', 'double']
    #identifier for string data type
    strings = ['char', 'var', 'text']
    #identifier for date/time data type
    dates = ['date', 'datetime', 'timestamp', 'time', 'year']

    if data_type is None:
        all_types = numerics + strings + dates
        data_type = random.choice(all_types)

    data_type_lower = data_type.lower()
    if any(n in data_type_lower for n in numerics):     #numeric types
        if 'int' in data_type_lower:
            return random.randint(-1000, 1000)
        else:  #float / decimal
            return round(random.uniform(-1000.0, 1000.0), 2)

    #string types
    elif any(s in data_type_lower for s in strings):
        length = random.randint(1, 12)  # random string length
        return ''.join(random.choices(string.ascii_letters + string.digits, k=length))

    #date/time types
    elif any(d in data_type_lower for d in dates):
        #generate a random date/time within +/- 10 years from today
        start = datetime.now() - timedelta(days=365 * 10)
        end = datetime.now() + timedelta(days=365 * 10)
        random_date = start + (end - start) * random.random()
        #return string in ISO format
        if 'date' in data_type_lower and 'time' not in data_type_lower:
            return random_date.strftime('%Y-%m-%d')
        elif 'time' in data_type_lower and 'date' not in data_type_lower:
            return random_date.strftime('%H:%M:%S')
        else:
            return random_date.strftime('%Y-%m-%d %H:%M:%S')

    #default fallback: string
    return str(random.randint(0, 1000))


def generate_type_violation(data_type=None):
    numerics = ['int', 'dec', 'float', 'double']
    strings = ['char', 'var', 'text']
    dates = ['date', 'datetime', 'timestamp', 'time', 'year']
    if data_type:
        if any(ident in data_type.lower()for ident in numerics):    #use string for numerics data type
            return generate_random_string()

        elif any(ident in data_type.lower()for ident in strings):   #use integer for strings data type
            MIN_INT = -sys.maxsize -1
            MAX_INT = sys.maxsize
            return random.randint(MIN_INT, MAX_INT)
        
        elif any(ident in data_type.lower() for ident in dates):    #use string for dates data type
            return generate_random_string()
    else:
        all_types = numerics + strings + dates
        choice = random.choice(all_types)
        if choice in numerics:
            return generate_random_string()
        elif choice in strings:
            MIN_INT = -sys.maxsize -1
            MAX_INT = sys.maxsize
            return random.randint(MIN_INT, MAX_INT)
        elif choice in dates:
            return generate_random_string()
 
def generate_domain_violation(data_type=None, length=0):
     #identifier for decimal data type
    numerics = ['int', 'dec', 'float', 'double']
    #identifier for string data type
    strings = ['char', 'var', 'text', ]
    #identifier for dates umeric data type
    dates = ['date', 'datetime', 'timestamp', 'time', 'year']
    if data_type is None:
        all_types = numerics + strings + dates
        data_type = random.choice(all_types)
    
    length = get_data_type_length(data_type)
    if length:
        length += 5

    invalid_dates = ['2024-02-30', '2025-13-07', '0000-11-15']
    if 'int' in data_type.lower():
        of = random.randint(1, 100)
        return 18446744073709551615 + of
    elif any(ident in data_type.lower() for ident in numerics):
        return "12345.123456789123456789123456789123456789123456789"
    elif any(ident in data_type.lower() for ident in strings):
        chars = string.ascii_letters + string.digits + string.punctuation
        if not length:
            length = random.randint(100,1000)
        return ''.join(random.choice(chars) for _ in range(length))
    elif 'date' in data_type.lower():
        return random.choice(invalid_dates)
    elif 'datetime' in data_type.lower() or 'timestamp' in data_type.lower():
        return random.choice(invalid_dates) + ' ' + '25:63:61'
    elif 'time' in data_type.lower():
        return '37:58:61'
    elif 'year' in data_type.lower():
        return '90000'

def get_data_type_length(data_type):
    if data_type:
        match = re.search(r'\((\d+)\)', data_type)
        if match:
            return int(match.group(1))
        return None
    return None

def generate_zero_or_empty(data_type):
    numerics = ['int', 'dec', 'float', 'double']
    strings =  ['char', 'var', 'text', 'date', 'datetime', 'timestamp', 'time', 'year']
    if data_type:
        if any(ident in data_type.lower() for ident in numerics):
            return 0
        elif any(ident in data_type.lower() for ident in strings):
            return ""
    elif not data_type:
        all_types = numerics + strings
        choice = random.choice(all_types)
        if choice in numerics:
            return 0
        else:
            return ""
    
def generate_random_normal_string(n=5):
    #first digit must be letter so it will not be interpreted as numeric by php type juggling
    first_char = random.choice(string.ascii_letters)
    #the rest of the string from letters and digits
    rest_of_string = ''.join(random.choice(string.ascii_letters + string.digits) for _ in range(n - 1))
    return first_char + rest_of_string

def generate_random_string():
    edge_strings = [
        "",               #empty string
        "a",              #minimal length
        " " * 1000,       #long whitespace
        "A" * 10_000      #very long string
    ]
    edge_weights = [0.1, 0.4, 0.4, 0.1]
    string_types = ['normal', 'boundary']
    weights = [0.7, 0.3]  # 70% normal, 30% boundary
    selected = random.choices(string_types, weights=weights, k=1)[0]

    if selected == 'normal':
        length = random.randint(5,100)
        return generate_random_normal_string(length)
    elif selected == 'boundary':
        return random.choices(edge_strings, weights=edge_weights, k=1)[0]

def generate_random_int():
    MIN_INT = -sys.maxsize -1
    MAX_INT = sys.maxsize
    int_values = [0, 1, -1, MIN_INT, MAX_INT, 2**64 - 1, -(2**64 - 1)]
    if random.random() < 0.5:
        return random.randint(MIN_INT, MAX_INT) #50% chance random large int
    else:
        return random.choice(int_values)        #50% chance boundary value

def strip_possessive_quantifiers(pattern: str) -> str:
    return (
        pattern
        .replace('*+', '*')
        .replace('++', '+')
        .replace('?+', '?')
    )

def generate_string_matches_pattern(pattern, pattern_type, input=None): 
    if not pattern or not pattern_type:
        return None
    
    if pattern_type == MatchingPattern.REGEX:
        try:
            match = rstr.xeger(pattern)
        except Exception as e:
            try:
                pattern = strip_possessive_quantifiers(pattern)
                match = rstr.xeger(pattern)
            except Exception as e:
                print(f"[xeger failed] pattern={pattern!r} error={e}")
                return ''

        if not input:
            return match

        anchored_start = pattern.startswith("^")
        anchored_end = pattern.endswith("$")

        if anchored_start and anchored_end:
            return match
        elif anchored_start and not anchored_end:
            return f"{match}{input}"
        elif anchored_end and not anchored_start:
            return f"{input}{match}"
        else:
            return f"{input}{match}{input}"
    
    #otherwise, literal string pattern is used with regular literal string processing (means without regex)
    #need to handle wildcards
    elif pattern_type == MatchingPattern.STRING_LIT:
        payload = ''
        for ch in pattern:
            if ch == '*':
                if not input:
                    payload += generate_random_normal_string(5)
                else:
                    payload += input
            elif ch == '?':
                if not input:
                    payload += random.choice(string.ascii_letters + string.digits)
                else:
                    payload += input[0]
            elif ch == '[':
                end = pattern.find(']', pattern.index(ch))
                options = pattern[pattern.index(ch)+1:end]
                if options.startswith('!'):
                    options = ''.join(set(string.ascii_letters + string.digits) - set(options[1:]))
                s += random.choice(options)
            else:
                payload += ch
        return payload

#pattern can be generated from an array separated by _ (underscore) by instrumentation
#input should be a string which refers to a value of a key in fuzzing parameters (c.fuzz_params) 
#                                        (patterns, MatchingPattern.REGEX, replacements, input)   
def generate_string_bypassing_replacement(patterns, pattern_type, replacements, input):
    if not patterns or not input:
        return None
    payload = None
    if pattern_type == MatchingPattern.REGEX:
        #to be written
        pass
    elif pattern_type == MatchingPattern.STRING_LIT:
        #currently, only do generation when the pattern is replaced by empty string
        flag = replacements.strip()
        if not flag:
            payload = bypassing_literal_pattern_replacement(patterns, input)
    return payload

def bypassing_literal_pattern_replacement(patterns, input):
    if not isinstance(input,str):
        return input
    copyin = input
    ptrns = patterns.split("_")
    applied = []
    for p in ptrns:
        if p in copyin:
            applied.append(p)
            copyin = copyin.replace(p,"")

    copyin = input
    for p in reversed(applied):
        tokens = split_by_pattern(copyin, p)
        new_tokens = interpolate_tokens(tokens,p)
        copyin = "".join(new_tokens)
    test = copyin
    for p in ptrns:
        test = test.replace(p,"")
    if test == input:
        return copyin
    else:
        return None

def split_by_pattern(s, pattern):
    if not isinstance(s,str) or not isinstance(pattern, str):
        return s
    if pattern == '':
        return s
    result = []
    i = 0
    plen = len(pattern)
    
    while i < len(s):
        if s.startswith(pattern, i):
            result.append(pattern)
            i += plen
        else:
            # accumulate non-pattern chars
            start = i
            while i < len(s) and not s.startswith(pattern, i):
                i += 1
            result.append(s[start:i])
    return result

def interpolate_tokens(tokens, pattern):
    new_tokens = []
    for tok in tokens:
        if tok == pattern:
            new_tok = interpolate_string_with_pattern(tok, pattern)
            new_tokens.append(new_tok)
        else:
            new_tokens.append(tok)
    return new_tokens
                    
#signal parameter is reserved for future use
def interpolate_string_with_pattern(a_str, pattern, signal=None):
    idx = len(a_str) // 2 #// is a floor function
    return a_str[:idx] + pattern + a_str[idx:]

def is_function_in_list(func, func_list):
    found = False
    for entry in func_list:
        if (func[Key.FUNCTION_NAME] == entry[Key.FUNCTION_NAME] and 
                func[Key.FILENAME] == entry[Key.FILENAME] and 
                func[Key.LINENO] == entry[Key.LINENO]):
                found = True
                break
    return found

'''
Check if the incoming vuln func is different from the vuln func in list.
Possible conditions
1. the incoming vuln func and vuln func in list both have no parameters in sink, then don't add. The vuln
    func in list already represent them.
2. the incoming vuln func has parameters in sink and vuln func in list does not, then add.
3. the incoming vuln func does not have parameters in sink, but vuln func in list has, then add.
4. both incoming vuln func and vuln func in list have parameters in sink, but the parameters are the same, 
    then don't add
5. both incoming vuln func and vuln func in list have parameters in sink, but the parameters are different, 
    then add.
'''
def compare_to_vuln_functions_in_candidate(vuln_func, c):
    added = False
    vf_variants = []
    for vf_inlist in c.vuln_functions:
        if (vuln_func[Key.FUNCTION_NAME] == vf_inlist[Key.FUNCTION_NAME] and 
            vuln_func[Key.FILENAME] == vf_inlist[Key.FILENAME] and 
            vuln_func[Key.LINENO] == vf_inlist[Key.LINENO]):
            vf_variants.append(vf_inlist)
    
    if not match_vuln_func_to_variants(vuln_func, vf_variants): #no variants match, then add to candidate vf list
        added = True
    return added

def match_vuln_func_to_variants(vf_1, vf_variants):
    for vf_2 in vf_variants:
        if both_vuln_functions_match(vf_1, vf_2):
            return True
    return False



def both_vuln_functions_match(vf_1, vf_2):
    if not vf_1.get(Key.PARAMS_IN_SINK) and not vf_2.get(Key.PARAMS_IN_SINK):
        return True
    elif vf_1.get(Key.PARAMS_IN_SINK) and not vf_2.get(Key.PARAMS_IN_SINK):
        return False
    elif not vf_1.get(Key.PARAMS_IN_SINK) and vf_2.get(Key.PARAMS_IN_SINK):
        return False
    else:
        for key in vf_1.get(Key.PARAMS_IN_SINK).keys():
            if key and key not in vf_2[Key.PARAMS_IN_SINK]:
                return False
        return True 




    

def get_updated_function(func, trace):
    updated_func = None
    if trace is not None:
        for entry in trace:
            if (func[Key.FUNCTION_NAME] == entry[Key.FUNCTION_NAME] and 
                    func[Key.FILENAME] == entry[Key.FILENAME] and 
                    func[Key.LINENO] == entry[Key.LINENO]):
                updated_func = entry
                return updated_func

def is_sql_payload_turn_to_logic(payload, query):
    NOT_LITERALS = {'or',  'and', 'not', 'xor',                                     #logic
                    '=', '!=', '>', '<', '>=', '<=', 'in', 'between', 'like', 'is', #comparison
                    '+', '-', '*', '/', '%',                                        #arithmetic                
                    'union', 'select', 'from', 'where', 'join', 'insert', 'update', #keywords
                    'delete','create', 'drop', 'alter', 'table', 'values', 'set'}
    if not payload or not query:
        return False
    payload = payload.lower()
    payload = re.split(r"(#|--\s+)", payload)[0]    #remove comment and everything after it
    payload = re.sub(r"/\*.*?\*/", " ", payload)    #remove /* ... */ comments
    payload_wo_quote = payload.replace("'", " ")    #remove single quotes
    payload_wo_quote = re.sub(r"\s+", " ", payload_wo_quote).strip() #normalize spaces

    payload_tokens = payload_wo_quote.split()                       # simple whitespace split; can be adjusted if needed
    payload_tokens_not_literal = copy.deepcopy(payload_tokens)

    query = query.lower()
    uncommented_query = re.split(r'#|--\s+', query)[0]      # remove comment since this is not part of query logic
    normalized_query = uncommented_query.replace("\\'", '') # remove escaped single quotes since sqlglot does not handle them well
    # print(normalized_query)

    try:
        tree = sqlglot.parse_one(normalized_query)
    except Exception as e:
        print(f"SQLglot error, cannot parse the bad-formed query. Error: {e}")
        return False    #for safe, return false, no logic. Parsing error is unveiled by error-based vuln check
                        #by analyzing error file produced by instrumentation
    max = len(payload_tokens_not_literal)
    for node in tree.find_all(sqlglot.exp.Literal): #match payloads with literals and removes them
        i=0
        while i < max :
            token = payload_tokens_not_literal[i]
            if token in node.this:
                payload_tokens_not_literal.remove(token)
                max -= 1
                break
            else:
                i+=1          
    for token in payload_tokens_not_literal:
        if token in NOT_LITERALS:
            continue
        else:
            payload_tokens_not_literal.remove(token)
    if not payload_tokens_not_literal:               #all tokens are in literal values
        return False
    
    seqs = payloadtoken_subsequences_in_query(payload,query)
    flag = False
    for token in payload_tokens_not_literal:
        for seq in seqs:
            for i in range(len(seq)):
                #if logic token found, just check if len(seq) > 1
                #better way is checking by using sql grammar. Compliance to grammar means no parse error, and
                #consisten with the result of query execution, which is successfull or return_value != False
                if seq[i] == token and len(seq) > 1: 
                    flag = True
                    return flag
    return flag

def payloadtoken_subsequences_in_query(payload, query):
    query_clean = clean_query(query)
    payload_clean = clean_query(payload)
    query_tokens = query_clean.lower().split()
    payload_tokens = payload_clean.lower().split()
    seqs = payloadtokens_subsequences_in_querytokens(payload_tokens, query_tokens)
    return seqs

def clean_query(query_str):
    query_str = re.split(r"(#|--\s+)", query_str)[0]                   #remove comment
    query_str = re.sub(r"/\*.*?\*/", " ", query_str, flags=re.DOTALL)  #remove block comments /* ... */
    query_str = query_str.replace("\\'", " ")                          #remove escaped quotes
    query_str = query_str.replace("'", " ").replace(";", " ")          #remove single quotes and semicolons
    query_str = re.sub(r"\s+", " ", query_str).strip()                 #normalize spaces
    query_str = query_str.lower()               
    return query_str

def find_next_match(token, query_tokens, start_index):
    for j in range(start_index, len(query_tokens)):
        if token == query_tokens[j]:
            return j
    return -1

def payloadtokens_subsequences_in_querytokens(payload_tokens, query_tokens):
    seqs = list()
    for i in range (0, len(payload_tokens)):
        k = i
        token = payload_tokens[k]
        found = False
        token_seqs = []
        cur_seq = []
        j=0
        while j < len(query_tokens):
            if token == query_tokens[j]:
                cur_seq.append(token)
                k += 1
                if k >= len(payload_tokens):
                    token_seqs.append(cur_seq)
                    break
                prev_token = token
                token = payload_tokens[k] 
                found = True
            elif token != query_tokens[j] and found:
                token_seqs.append(cur_seq)
                
                #find next match, in case the token appears in more than one locations
                next_match_index = find_next_match(prev_token, query_tokens, j+1)
                if next_match_index != -1:
                    token = prev_token
                    k = k-1
                    j = next_match_index-1
                    cur_seq = []
                else:
                    break
            j+=1
        longest_token_seq = max(token_seqs, key=len) if token_seqs else []
        seqs.append(longest_token_seq)
    return seqs

def create_json_dumpable_list_dict(list_dict):
    copy_ld = copy.deepcopy(list_dict)
    for entry in copy_ld:
        keys_to_remove = [key for key in entry if isinstance(key, tuple)]
        for key in keys_to_remove:
            del entry[key]
    
        '''
        If entry contains params_in_sink and func_sanit_rep, the case from safeseq_confirmation implemented by
        function: zimpaf_save_safeseq_confirmation(vuln_func, c), remove them. 
        '''
        if Key.PARAMS_IN_SINK in entry and entry.get(Key.PARAMS_IN_SINK):
            del entry[Key.PARAMS_IN_SINK]
        if Key.FUNC_SANIT_REP in entry and entry.get(Key.FUNC_SANIT_REP):
            del entry[Key.FUNC_SANIT_REP]
    return copy_ld

def any_params_length_less_five(fuzz_params):
    for req_part in fuzz_params:
        params = fuzz_params[req_part]
        for param in params:
            p = fuzz_params[req_part][param]
            p = str(p)
            if len(p.strip()) < 5:
                return True
    return False

def find_successor_function(token, key_to_look, trace):
    successors = []
    for func in trace:
        if (key_to_look in func and func[key_to_look] == token):
            successors.append(func)
    return successors

#This function captures all parameters from bind_param/bindValue and execute functions
#Parameter successors can receive a list of functions and also a single function
def params_in_bind_execute_functions(successors):
    if not successors:
        return None
    if not isinstance(successors, list):
        successors = [successors]
    params = ""
    for func in successors:
        if Key.PARAMETERS in func and func[Key.PARAMETERS]:
            if func[Key.FUNCTION_NAME] == 'mysqli_stmt_bind_param':
                prm = func[Key.PARAMETERS].split("_",2)[2]
                params += prm + "_"
            elif func[Key.FUNCTION_NAME] in ['mysqli_stmt::bind_param', 'mysqli_stmt_execute']:
                prm = func[Key.PARAMETERS].split("_",1)[1]
                params += prm + "_"
            elif func[Key.FUNCTION_NAME] in ['PDOStatement::bindParam', 'PDOStatement::bindValue']:
                prm = func[Key.PARAMETERS].split("_")[1]
                params += prm + "_"
            elif func[Key.FUNCTION_NAME] in ['mysqli_stmt::execute', 'PDOStatement::execute']:
                prm = func[Key.PARAMETERS]
                params += prm + "_"
    if params:
        params = params.rstrip("_")
        # params = params[:-1]      #this is the same as preceeding line
    return params

#params contains all parameters from bind or execute functions concatenated with _ (underscore)
#this is only used for non-sql injection vulnerability, e.g xss. the resulted query does not neccessarily reflect
#the actual query executed, especially in whether literals are quoted or not.
def substitute_placeholders_with_params(vuln_func_query, params_list): 
    if not vuln_func_query or not params_list:
        return None
    query = vuln_func_query
    combined = []
    for params in params_list:
        if isinstance(params, str):
            parts = params.split("_")
            parts = parts[1:]
            combined += parts
    values = iter([str(p) for p in combined])
 
    def quote_and_escape(v):
        #simple escaping for single quotes inside value
        return "'" + v.replace("'", "\\'") + "'"

    result = []
    i = 0
    qlen = len(query)
    while i < qlen:
        ch = query[i]
        if ch == "?":
            try:
                val = next(values)
            except Exception as e:
                print(f"Not enough params supplied for '?' placeholders. Error: {e}")
                return None
            result.append(quote_and_escape(val))
            i += 1
        elif ch == ":":
            #match :name (letters, digits, underscore, starting with letter/underscore)
            m = re.match(r":([A-Za-z_]\w*)", query[i:])
            if m:
                try:
                    val = next(values)
                except Exception as e:
                    print(f"Not enough params supplied for ':' placeholders. Error: {e}")
                    return None
                result.append(quote_and_escape(val))
                i += len(m.group(0))
            else:
                #lone ':' or weird token — copy one char and move on
                result.append(ch)
                i += 1
        else:
            result.append(ch)
            i += 1
    return "".join(result)

def params_in_branches_in_fuzz_params(param_cmp_rep, out_key, fuzz_params):
    key1 = param_cmp_rep.get(Key.OP1_INPUT_PARAM)
    key2 = param_cmp_rep.get(Key.OP2_INPUT_PARAM)
    
    if key1 and key2:
        return key1 in fuzz_params[out_key] and key2 in fuzz_params[out_key]
    if key1:
        return key1 in fuzz_params[out_key]
    if key2:
        return key2 in fuzz_params[out_key]
    
    return False  # neither key exists

#for sanitation function, there is no vulnerability
def get_sink(func, vulnerability=None):
    if vulnerability == Vulnerability.CODE_EXEC:
        return func.get(Key.COMMAND)
    elif vulnerability == Vulnerability.PATHTRAVS:
        return func.get(Key.PATH)
    elif vulnerability == Vulnerability.SQLI:
        return func.get(Key.QUERY)
    elif vulnerability == Vulnerability.UNSERIALIZE:
        return func.get(Key.SERIALIZED_STRING)
    elif vulnerability == Vulnerability.XXE:
        return func.get(Key.XML_PAYLOAD)
    elif func.get(Key.STRING):
        return func[Key.STRING]
    return None
   




