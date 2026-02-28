import json
import os
import re
from sqlglot.expressions import Literal, Placeholder, Null, Boolean
from libzimpaf.constants import (APICallTraceStatus, Key, VulnFuncStatus, AllFlags, OplineType, 
                                 Vulnerability, SQLQueryType, MatchingPattern, CodeBase)
import utils
import time
import libzimpaf.config as cfg
import base64


CODE_EXEC_VULN_FUNCTIONS = {'assert', 'eval', 'system', 'exec', 'passthru', 'shell_exec',
                           'popen', 'proc_open'} #8 functions

PATHTRAVS_VULN_FUNCTIONS = {'include', 'include_once', 'require', 'require_once', 'chgrp',
                           'chown', 'chmod', 'copy', 'delete', 'file', 'file_get_contents',
                           'fopen', 'glob', 'lchgrp', 'lchown', 'link', 'mkdir', 'move_uploaded_file',
                           'parse_ini_file', 'parse_ini_string', 'readfile', 'rename',
                           'rmdir', 'stat', 'symlink', 'tempnam', 'touch', 'unlink', 'scandir', 'header',
                           'clearstatcache', 'disk_free_space', 'disk_total_space', 'file_get_contents',
                           'fileatime', 'filectime', 'filegroup', 'fileinode', 'filemtime', 'fileowner',
                           'filesize', 'fileperms', 'filetype', 'linkinfo',
                           'lstat', 'readlink'} #46 functions

SQLI_VULN_FUNCTIONS = {'mysqli::query', 'mysqli::execute_query', 'mysqli::real_query', 'mysqli::multi_query', 
                         'mysqli::prepare', 'PDO::query', 'PDO::prepare', 'PDO::exec', 'mysqli_query', 
                         'mysqli_execute_query', 'mysqli_real_query', 'mysqli_multi_query', 'mysqli_prepare'} #13 functions

UNSERIALIZE_VULN_FUNCTIONS = {'unserialize', 'yaml_parse', #'Phar::decompress',
                              'yaml_parse_file', 'igbinary_unserialize'} #4functions

XXE_VULN_FUNCTIONS = {'DOMDocument::load', 'DOMDocument::loadXML', 'XMLReader::XML', #'xml_set_external_entity_ref_handler'
                      'XMLReader::open', 'XMLReader::read', 'simplexml_load_string', 'simplexml_load_file',
                      'xml_parse'}       #8 functions

SANITATION_FUNCTIONS = {'mysqli_real_escape_string','mysqli::real_escape_string', 'PDO::quote', 
                        'htmlspecialchars', 'htmlentities','addslashes', 'stripslashes', 'strip_tags', 
                        'preg_replace', 'preg_match', 'realpath', 'basename', 'escapeshellarg',
                        'escapeshellcmd', 'str_replace', 'strpos', 'stripos', 'filter_var', 'filter_input',
                        'filter_var_array', 'filter_input_array', 'libxml_disable_entity_loader','is_numeric', 
                        'base64_decode', 'json_decode', 'fnmatch', 'is_file',
                        'file_exists', 'is_dir', 'is_excutable', 'is_link', 'is_readable', 'is_writable',
                        'is_uploaded_file', 'dirname', 'pathinfo'} #36 functions

DIE_EXIT_FUNCTIONS      = {'die', 'exit'}
CODE_EXEC_SFUNCTIONS    = {'escapeshellarg', 'escapeshellcmd'}
PATHTRAVS_SFUNCTIONS    = {'basename', 'realpath','strpos', 'stripos'}
SQL_SFUNCTIONS          = {'mysqli_real_escape_string','mysqli::real_escape_string', 'PDO::quote'}
UNSERIALIZE_SFUNCTIONS  = {'preg_match'}
XXE_SFUNCTIONS          = {'libxml_disable_entity_loader'}
XSS_SFUNCTIONS          = {'htmlspecialchars', 'htmlentities', 'strip_tags'}

#generic string funcs seems can be classified into match or escape, but this time, they are not, the current mutation
#strategy can fulfill their roles. Moreover, filter var and its variants have so many flags.
'''GENERIC_STRING_SFUNCTIONS   = {'addslashes', 'filter_var', 'filter_input', 
                             'filter_var_array', 'filter_input_array' } 
'''
STRING_MATCH_FUNCTIONS      = {'preg_match', 'fnmatch'}
STRING_REPLACE_FUNCTIONS    = {'preg_replace', 'str_replace'}
# ESCAPE_FUNCTIONS            = set() #reserved for future use, if necessary
DECODE_FUNCTIONS            = {'base64_decode', 'json_decode'}
#ENCODE_FUNCTIONS            = set() #reserved for future use, if necessary
NUMERIC_MATCH_FUNCTIONS     = {'is_numeric'}
FILESYSTEM_MATCH_FUNCTIONS  = {'is_file','file_exists'}

BIND_EXECUTE_FUNCTIONS   = {'mysqli_stmt_bind_param', 'mysqli_stmt_execute',
                            'mysqli_stmt::bind_param', 'mysqli_stmt::execute', 
                            'PDOStatement::bindParam', 'PDOStatement::bindValue', 'PDOStatement::execute'}

LEN_SANITATION_FUNCTIONS = len(SANITATION_FUNCTIONS)


maybe_safe_sequence = \
    {Key.SQLI_SAFESEQ:{     #For sqli we use dictionaries, but for others use list of list.
        'mysqli_prepare': [['mysqli_prepare', 'mysqli_stmt_bind_param', 'mysqli_stmt_execute'],
                           ['mysqli_prepare', 'mysqli_stmt_execute'],  #only if execute binds the parameters
                           ['mysqli_real_escape_string', 'mysqli_prepare', 'mysqli_stmt_execute'] #if query param inside quote    
                          ],
        'mysqli::prepare':[['mysqli::prepare', 'mysqli_stmt::bind_param', 'mysqli_stmt::execute'],
                           ['mysqli::prepare', 'mysqli_stmt::execute'],  #only if execute bind the parameters
                           ['mysqli::real_escape_string', 'mysqli::prepare', 'mysqli_stmt::execute'] #only if param inside quote
                          ],
        'PDO::prepare':   [['PDO::prepare', ('PDOStatement::bindParam', 'PDOStatement::bindValue'), 
                           'PDOStatement::execute'],
                           ['PDO::prepare', 'PDOStatement::execute'],    #only if execute bind the parameters
                           ['PDO::quote', 'PDO::prepare', 'PDOStatement::execute'] #only query if param inside quote
                          ],                           
        Key.QUERY_FUNC:   [[('mysqli_real_escape_string','mysqli::real_escape_string'), #only if param inside 
                             ('mysqli::query', 'mysqli::execute_query', 'mysqli::real_query',          #quote
                              'mysqli::multi_query', 'mysqli_query', 'mysqli_execute_query', 
                              'mysqli_real_query', 'mysqli_multi_query')],
                              ['PDO::quote', ('PDO::query', 'PDO::exec')] #only if query param inside quote
                          ]                   
        }, 
    Key.CODE_EXEC_SAFESEQ: [[('escapeshellarg', 'escapeshellcmd'), 
                            ('assert', 'eval', 'system', 'exec', 'passthru', 'shell_exec', 'popen','proc_open')]],

    Key.PATHTRAVS_SAFESEQ: [[('basename', 'realpath'), 
                            ('include', 'include_once', 'require', 'require_once', 'chgrp', 'chown', 'chmod',
                             'copy', 'delete', 'dirname', 'file', 'file_get_contents', 'fopen', 'glob',
                             'lchgrp', 'lchown', 'link', 'mkdir', 'move_uploaded_file', 'parse_ini_file',
                             'parse_ini_string', 'pathinfo', 'readfile', 'rename', 'rmdir', 'stat', 'symlink',
                             'tempnam', 'touch', 'unlink', 'scandir')]],

    # Key.UNSERIALIZE_SAFESEQ: [['preg_match', ('unserialize','Phar::decompress', 'yaml_parse', 'yaml_parse_file',
    #                                         'unpack', 'igbinary_unserialize')]],

    #safe when libxml_disable_entity_loader is true, no need to check the libxml flags. 
    #Works only in php 7.xx and does not work/deprecated in php 8.xx
    Key.XXE_SAFESEQ: [['libxml_disable_entity_loader', ('DOMDocument::load', 'DOMDocument::loadXML', 'XMLReader::XML', 
                      'XMLReader::open', 'simplexml_load_string', 'simplexml_load_file',
                      'xml_set_external_entity_ref_handler', 'xml_parse')]],   
                                                                                                                      
    Key.XSS_SAFESEQ:[[('htmlspecialchars', 'htmlentities', 'strip_tags'), ('mysqli::query', 'mysqli::execute_query', 
                        'mysqli::real_query', 'mysqli::multi_query', 'mysqli::prepare', 'PDO::query', 'PDO::prepare',
                        'PDO::exec', 'mysqli_query', 'mysqli_execute_query', 'mysqli_real_query','mysqli_multi_query',
                        'mysqli_prepare', 'mysqli_stmt_bind_param', 'mysqli_stmt_execute','mysqli_stmt::bind_param', 
                        'mysqli_stmt::execute', 'PDOStatement::bindParam', 'PDOStatement::bindValue', 
                        'PDOStatement::execute')]] 
}                                                                           


def set_api_call_trace_status(candidate, ff_vuln_function_hashes): #f_trace is a list of function call traces in JSON format
    trace_status = 0
    f_trace = candidate.function_trace
    prev_sanit_func = None 
    '''
    For evaluation data
    '''
    # num_functions = len(candidate.function_trace)
    # num_vuln_functions = 0
    # num_sanit_functions = 0
    # num_untainted_vuln_functions = 0
    # num_trav_functions_in_code_base = 0
    if f_trace is None:
        trace_status |= APICallTraceStatus.NO_TRACE
        return trace_status
    for func in f_trace[:]: #shallow copy because possible to remove the func whose opline is const
        is_vuln_func = False
        processing_err_flag = False
        func_name = func[Key.FUNCTION_NAME] 
        if func_name in DIE_EXIT_FUNCTIONS:
            trace_status |= APICallTraceStatus.EXIST_DIE_EXIT_FUNCTION
            continue

        elif func_name in SANITATION_FUNCTIONS:
            # num_sanit_functions += 1
            if func != prev_sanit_func:
                trace_status |= APICallTraceStatus.EXIST_SANITATION_FUNCTION
                candidate.sanit_functions.append(func)
                prev_sanit_func = func
            else:
                f_trace.remove(func)
            continue

        elif func_name in CODE_EXEC_VULN_FUNCTIONS:
            # num_vuln_functions += 1

            trace_status |= APICallTraceStatus.EXIST_CODE_EXEC_VULN_FUNCTION
            if Key.COMMAND_OPLINE_TYPE in func and  func[Key.COMMAND_OPLINE_TYPE] != OplineType.IS_CONST:
                is_vuln_func = True
                f_param = func[Key.COMMAND]
            else:
                # num_untainted_vuln_functions += 1

                print(f"\033[32mVULN FUNCTION: {func[Key.FUNCTION_NAME]} is safe, PARAMETER IS CONSTANT. \033[0m")
                print(f"Location: {func[Key.FILENAME]}:{func[Key.LINENO]}")
                f_trace.remove(func) 
                continue
        elif func_name in PATHTRAVS_VULN_FUNCTIONS:
            # num_vuln_functions += 1

            trace_status |= APICallTraceStatus.EXIST_PATHTRAVS_VULN_FUNCTION
            if Key.PATH_OPLINE_TYPE in func and func[Key.PATH_OPLINE_TYPE] == OplineType.IS_CONST:
                # num_untainted_vuln_functions += 1

                print(f"\033[32mVULN FUNCTION: {func[Key.FUNCTION_NAME]} is safe, PARAMETER IS CONSTANT. \033[0m")
                print(f"Location: {func[Key.FILENAME]}:{func[Key.LINENO]}")
                f_trace.remove(func)
                continue 
            elif traversal_function_path_in_codebase(func):
                # num_trav_functions_in_code_base += 1
                f_trace.remove(func) 
                continue
            elif Key.PATH_OPLINE_TYPE in func and func[Key.PATH_OPLINE_TYPE] != OplineType.IS_CONST:
                is_vuln_func = True
                f_param = func[Key.PATH]
            
            
        elif func_name in SQLI_VULN_FUNCTIONS:
            # num_vuln_functions += 1

            trace_status |= APICallTraceStatus.EXIST_SQLI_VULN_FUNCTION
            is_vuln_func = True
            if Key.QUERY_FUNC in func and func[Key.QUERY_FUNC]== 1:
                if Key.QUERY_OPLINE_TYPE in func and func[Key.QUERY_OPLINE_TYPE] == OplineType.IS_CONST:
                    # num_untainted_vuln_functions += 1

                    is_vuln_func = False
                    print(f"\033[32mVULN FUNCTION: {func[Key.FUNCTION_NAME]} is safe, PARAMETER IS CONSTANT. \033[0m")
                    print(f"Location: {func[Key.FILENAME]}:{func[Key.LINENO]}")
                    f_trace.remove(func)
                    continue
            if is_vuln_func:
                params_quoted, params_numeric, params_unquoted = utils.get_and_classify_query_params(func[Key.QUERY])
                if params_unquoted is None or params_quoted is None or params_numeric is None:
                    processing_err_flag = True
                    
                func[Key.PARAMS_QUOTED] = params_quoted
                func[Key.PARAMS_NUMERIC] = params_numeric
                func[Key.PARAMS_UNQUOTED] = params_unquoted
                f_param = func[Key.QUERY]

        elif func_name in UNSERIALIZE_VULN_FUNCTIONS:
            # num_vuln_functions += 1

            trace_status |= APICallTraceStatus.EXIST_UNSERIALIZE_VULN_FUNCTION
            if Key.SERIALIZED_STRING_OPLINE_TYPE in func and func[Key.SERIALIZED_STRING_OPLINE_TYPE] != OplineType.IS_CONST:
                is_vuln_func = True
                f_param = func[Key.SERIALIZED_STRING]
            else:
                # num_untainted_vuln_functions += 1

                print(f"\033[32mVULN FUNCTION: {func[Key.FUNCTION_NAME]} is safe, PARAMETER IS CONSTANT. \033[0m")
                print(f"Location: {func[Key.FILENAME]}:{func[Key.LINENO]}")
                f_trace.remove(func)
                continue
        elif func_name in XXE_VULN_FUNCTIONS:
            # num_vuln_functions += 1

            trace_status |= APICallTraceStatus.EXIST_XXE_VULN_FUNCTION
            if Key.XML_PAYLOAD_OPLINE_TYPE in func and func[Key.XML_PAYLOAD_OPLINE_TYPE] != OplineType.IS_CONST:
                is_vuln_func = True
                f_param = func[Key.XML_PAYLOAD]
            else:
                # num_untainted_vuln_functions += 1

                print(f"\033[32mVULN FUNCTION: {func[Key.FUNCTION_NAME]} is safe, PARAMETER IS CONSTANT. \033[0m")
                print(f"Location: {func[Key.FILENAME]}:{func[Key.LINENO]}")
                f_trace.remove(func)
                continue

        if is_vuln_func:
            # vuln_func_hash = utils.get_function_hash(func)
            # if vuln_func_hash not in ff_vuln_function_hashes:
            #     ff_vuln_function_hashes.add(vuln_func_hash)

            #if not in candidate's vulnerable function list, add it to the list
            if not utils.is_function_in_list(func, candidate.vuln_functions):
                if Key.VULN_FUNC_STATUS not in func:
                    func[Key.VULN_FUNC_STATUS] = VulnFuncStatus.FUZZED
                if Key.NUM_FUNC_ITERATIONS not in func:
                    func[Key.NUM_FUNC_ITERATIONS] = 0
                if Key.NUM_PARAMS_ITERATIONS not in func:
                    func[Key.NUM_PARAMS_ITERATIONS] = 0
                func[Key.PARAMS_IN_SINK] = get_params_in_sink(candidate.fuzz_params, f_param, func, 
                                                              candidate.http_target)
                func[Key.FUNC_SANIT_REP] = None
                candidate.vuln_functions.append(func)

            else:
                '''
                Function is already in the list. If library/centralized function, it is possible the function is
                called from several lines in one execution path with different conditions: no parameter in sink, 
                different parameters in sink. Add the function for every condition.
                '''
                func[Key.PARAMS_IN_SINK] = get_params_in_sink(candidate.fuzz_params, f_param, func,
                                                              candidate.http_target)
                if utils.compare_to_vuln_functions_in_candidate(func, candidate):
                    if Key.VULN_FUNC_STATUS not in func:
                        func[Key.VULN_FUNC_STATUS] = VulnFuncStatus.FUZZED
                    if Key.NUM_FUNC_ITERATIONS not in func:
                        func[Key.NUM_FUNC_ITERATIONS] = 0
                    if Key.NUM_PARAMS_ITERATIONS not in func:
                        func[Key.NUM_PARAMS_ITERATIONS] = 0
                    func[Key.PARAMS_IN_SINK] = get_params_in_sink(candidate.fuzz_params, f_param, func,
                                                                  candidate.http_target)
                    func[Key.FUNC_SANIT_REP] = None
                    candidate.vuln_functions.append(func)
          
    if processing_err_flag:
        candidate.api_call_status |= APICallTraceStatus.INCOMPLETE     
    candidate.api_call_status = trace_status

'''
This function main purpose is to update sanitation functions be used in producing sanitation report by
function: sanitation_report. The sanitation report is used to perform sanitation aware mutation.
'''
def traversal_function_path_in_codebase(vuln_func):
    if (vuln_func[Key.PATH].startswith(CodeBase.WEBROOT)):
        print(f"\033[32mVULN FUNCTION: {vuln_func[Key.FUNCTION_NAME]} has CODE BASE PATH AS SINK, IGNORED FROM VF LIST. \033[0m")
        print(f"Location: {vuln_func[Key.FILENAME]}:{vuln_func[Key.LINENO]}")
        return True
    else:
        return False


def update_sanitation_functions(c, trace_is_updated=False, vuln_type=None):
    #If trace is just updated, then use c.function_trace directly
    #otherwise, load from shared tmpfs, so that the trace and input parameters are consistent, not stale
    new_sanit_funcs = None
    #the caller function just updated the trace
    if trace_is_updated:                        #caller is function_based_check(...) and sanitation_report
        new_trace = c.function_trace            #that is called from copy_candidate(...)
    elif not trace_is_updated and vuln_type:    #caller is sanitation_report, which is called by
        cov_id = c.parent_cov_id                #fuzz_sqli_function(...) and generic_fuzz_vuln_function(...)
        func_traces_path = os.path.join("/shared-tmpfs/function-call-traces",f"{cov_id}.json")
        c.function_trace = utils.load_json_file(func_traces_path)
        new_trace = c.function_trace
    else:                               #caller is sanitation report, which is called by fuzz_candidate(...)
        if c.coverage_id.endswith("_"): #probably restored candidate that has never been submitted to web server
            func_traces_path = f"/shared-tmpfs/function-call-traces/{c.coverage_id[:-1]}.json"
        else:
            func_traces_path = os.path.join("/shared-tmpfs/function-call-traces",f"{c.coverage_id}.json")
        c.function_trace = utils.load_json_file(func_traces_path)
        new_trace = c.function_trace
    if new_trace:
        sanit_funcs = ( STRING_REPLACE_FUNCTIONS | STRING_MATCH_FUNCTIONS | NUMERIC_MATCH_FUNCTIONS 
                        | DECODE_FUNCTIONS | FILESYSTEM_MATCH_FUNCTIONS)
        new_sanit_funcs = [func for func in new_trace if func.get(Key.SANITATION_FLAG) and
                                                        func.get(Key.FUNCTION_NAME) in sanit_funcs]
        return new_sanit_funcs
    return None

       
def is_vuln_funcs_executed_in_safe_seq(c, start_time=None, vuln_func=None): #c is candidate
    if start_time is None:
        start_time = time.time()
    if vuln_func:
        vf_list = [vuln_func]
    else:
        vf_list = c.vuln_functions

    for func in vf_list:
        is_safe = False
        if func[Key.FUNCTION_NAME] in CODE_EXEC_VULN_FUNCTIONS:
            patterns = maybe_safe_sequence[Key.CODE_EXEC_SAFESEQ]
            sink_key = Key.COMMAND
            opline_type_key = Key.COMMAND_OPLINE_TYPE 
            is_safe = is_vuln_func_exec_safe(func,c, patterns, sink_key, opline_type_key)
        elif func[Key.FUNCTION_NAME] in PATHTRAVS_VULN_FUNCTIONS:
            patterns = maybe_safe_sequence[Key.PATHTRAVS_SAFESEQ]
            sink_key = Key.PATH
            opline_type_key = Key.PATH_OPLINE_TYPE
            is_safe = is_vuln_func_exec_safe(func,c, patterns, sink_key, opline_type_key)
        elif func[Key.FUNCTION_NAME] in SQLI_VULN_FUNCTIONS:
            is_safe = is_sqli_func_exec_safe(func, c)
        elif func[Key.FUNCTION_NAME] in UNSERIALIZE_VULN_FUNCTIONS:
            # patterns = maybe_safe_sequence[Key.UNSERIALIZE_SAFESEQ]
            # sink_key = Key.SERIALIZED_STRING
            # opline_type_key = Key.SERIALIZED_STRING_OPLINE_TYPE
            # is_safe = is_vuln_func_exec_safe(func,c, patterns, sink_key, opline_type_key)
            return is_safe  #not implemented yet, so return False
        elif func[Key.FUNCTION_NAME] in XXE_VULN_FUNCTIONS:
            patterns = maybe_safe_sequence[Key.XXE_SAFESEQ]
            sink_key = Key.XML_PAYLOAD
            opline_type_key = Key.XML_PAYLOAD_OPLINE_TYPE
            is_safe = is_xxe_func_exec_safe(func,c)
            
        if is_safe:
            elapsed = time.time() - start_time
            seconds = int(elapsed)
            print(f"\033[32mVULN FUNCTION: {func[Key.FUNCTION_NAME]} is executed SAFELY. \033[0m")
            print(f"\033[32mDECISION IS MADE AFTER: {seconds} seconds. \033[0m")
            print(f"DETAIL:{func[Key.FUNCTION_NAME]}")
            print(f"LOCATION: {func[Key.FILENAME]}:{func[Key.LINENO]}")
            update_vuln_func_status_and_remove(func, c, VulnFuncStatus.IN_SAFESEQ)
    

#Check if vuln function is executed in safe sequence:
#vf is one of vuln function in candidate.vuln_functions
#c is candidate
#patterns is list of list of safe sequence pattern: e.g. maybe_safe_sequence[Key.CODE_EXEC_SAFESEQ]] or
#                                                        maybe_safe_sequence[Key.SQLI_SAFESEQ]["mysqli_prepare"]
#key is a key in param: vf, which is a target sink for fuzz input, e.g. Key.COMMAND for code exec vuln func or
#                                                                       Key.PATH for pathtravs vuln func
def is_vuln_func_exec_safe(vf, c, patterns, sink_key, opline_type_key): 
    target_sink = vf[sink_key]           #this is the vuln func parameter where fuzz inputs is expected land
    if opline_type_key in vf and vf[opline_type_key] == OplineType.IS_CONST:
        return True
    trace = c.function_trace
    sanitized_string = None
    for pattern in patterns:
        pi = 0                      #index in pattern to be matched
        plen = len(pattern)
        sanitized_string = None
    
        for func in trace:
            target = pattern[pi]
            match_prev = False      #to identify repeated function call in sequence, e.g. bindParam, bindValue
            count = 0       #count the number of the same functions with different parameters is called
                            #e.g. bindParam or bindValue
            # Normalize to a tuple so both string and tuple/list in safe seq list are handled
            if isinstance(target, (tuple, list)):
                options = tuple(target)
            else:
                options = (target,)
            
            func_name = func[Key.FUNCTION_NAME]
            if func_name in options:
                #case 1: the function in trace is a sanitation function.
                if func_name in SANITATION_FUNCTIONS:           #don't match to vuln function, but match the fuzz inputs to string to be sanitized
                    if Key.STRING in func and func[Key.STRING]: #sanitation function has to have string parameter, otherwise it is weird, ignore this function call
                        tainted_string = func[Key.STRING]       #the string to be sanitized
                    elif Key.PATH in func and func[Key.PATH]:   #for path traversal, the path is the string to be sanitized
                        tainted_string = func[Key.PATH]
                    params_reach_sink = utils.match_fuzz_params_to_function_param(c.fuzz_params, tainted_string)
                    if not params_reach_sink: #the sanitation function does not use our input, seems bogus
                        continue

                    print(func_name)
                    pi += 1
                    sanitized_string = func[Key.RETURN_VALUE]
                #case 2: the function in trace matches with vuln function vf
                elif (func_name == vf[Key.FUNCTION_NAME] and func[sink_key] == target_sink):
                    if sanitized_string:
                        input_matched_sink = utils.is_payload_in_sink(sanitized_string, target_sink) 
                    else:
                        params_reach_sink = utils.match_fuzz_params_to_function_param(c.fuzz_params, target_sink) 
                        input_matched_sink = params_reach_sink
                    if input_matched_sink:
                        print(func_name)
                        pi +=1
                    else:
                        continue
            elif func_name not in options and pi > 0:
                prev = pattern[pi-1]
                if isinstance(prev, (tuple, list)):
                    match_prev = func_name in prev
                else:
                    match_prev = func_name == prev

                if match_prev:
                    count+=1 #repeated function call
                    print(func_name)
                else:
                    continue
            else:
                continue
            if pi == plen:
                return True       
    return False  # Not all pattern elements were matched

def get_params_in_sink(params, sink, func,http_target): #params, for instance c.fuzz_params or c.fixed_params which is a 2 level dictionaries
    if Key.PARAMS_IN_SINK not in func or not func[Key.PARAMS_IN_SINK]: 
        params_in_sink = {}
    else:
        params_in_sink = func[Key.PARAMS_IN_SINK]
    for outer_key in params.keys():
        for inner_key, v in params[outer_key].items():
            if utils.is_payload_in_sink(v, sink):
                key = (outer_key, inner_key)
                if key not in params_in_sink or not params_in_sink[key]:
                    params_in_sink[(outer_key, inner_key)] = {Key.DATA_TYPE : None, Key.QUOTED:None}
                    if (func[Key.FUNCTION_NAME] in SQLI_VULN_FUNCTIONS or 
                        func[Key.FUNCTION_NAME] in BIND_EXECUTE_FUNCTIONS):
                        if func[Key.FUNCTION_NAME] in SQLI_VULN_FUNCTIONS:
                            table_col_list = utils.parse_tables_columns_from_query(func[Key.QUERY])
                            if table_col_list is None:
                                return params_in_sink
                        else:
                            table_col_list = utils.parse_tables_columns_from_query(sink)
                            if table_col_list is None:
                                return params_in_sink
                        if table_col_list:
                            tables_set = {row[Key.TABLE] for row in table_col_list if row[Key.TABLE]}
                            # tables_desc_dict = utils.get_tables_cols_desc_from_db(tables_set, 'localhost', 'dvwa_user', 'password', 'dvwa') 
                            if not tables_set:
                                return params_in_sink
                            HOST, USER, PASSWD, DB = cfg.get_db_config(http_target)
                            tables_desc_dict = utils.get_tables_cols_desc_from_db(tables_set, HOST, USER, PASSWD, DB) 
                            if not tables_desc_dict:
                                print(f"Failed to get table/column description from database {DB}.")
                                return params_in_sink
                            for tc in table_col_list:
                                col_desc_tuples_list = tables_desc_dict.get(tc[Key.TABLE], [])
                                for col_desc in col_desc_tuples_list:
                                    if col_desc and tc[Key.COLUMN] == col_desc[0]:
                                        tc[Key.DATA_TYPE] = col_desc[1] #now we have data type of the column to fuzz
                                        break
                                # if Key.DATA_TYPE not in tc:
                                #     tc[Key.DATA_TYPE] = tc.get(Key.DATA_TYPE, None)
                                    
                            for tc in table_col_list:
                                if str(params[outer_key][inner_key]) == tc[Key.VALUE]:
                                    if Key.DATA_TYPE in tc and tc[Key.DATA_TYPE]:
                                        params_in_sink[(outer_key, inner_key)][Key.DATA_TYPE] = tc.get(Key.DATA_TYPE)
                                    if Key.QUOTED in tc and tc[Key.QUOTED]:
                                        params_in_sink[(outer_key, inner_key)][Key.QUOTED] = tc[Key.QUOTED]
                                    break
    return params_in_sink

def is_sqli_func_exec_safe(vf, c):
    qfunc_num_params_quoted = 0
    params_unquoted = vf.get(Key.PARAMS_UNQUOTED)
    '''
    Branch check
    1. When trace complete, either query func, e.g. mysqli_query or prepare func, e.g. mysqli::prepare, if it has 
       unquoted parameter means it cannot be safe. return false
    2. In case params unquoted is None/not available, if query func, check existence of unquoted from params_in_sink
       If it has, then mysqli_escape_string won't work, not safe.
    3. If trace is incomplete, safe execution is not guaranteed, return false
    '''
    
    if (not c.api_call_status & APICallTraceStatus.INCOMPLETE) and params_unquoted:
        return False
   
    elif Key.PARAMS_IN_SINK in vf and vf.get(Key.PARAMS_IN_SINK) and Key.QUERY_FUNC in vf:
        for k, v in vf.get(Key.PARAMS_IN_SINK).items():
            if v.get(Key.QUOTED) is not True:
                return False
            qfunc_num_params_quoted += 1
    elif c.api_call_status & APICallTraceStatus.INCOMPLETE: 
        return False
   
    vf_name = vf[Key.FUNCTION_NAME] #vf is the vulnerable sql function
    vf_query = vf[Key.QUERY]
    if qfunc_num_params_quoted == 0:
        qfunc_num_params_quoted = len(vf[Key.PARAMS_QUOTED]) if vf[Key.PARAMS_QUOTED] else 0
    target_sink = None
    sanitized_inputs = []
    if Key.QUERY_FUNC in vf and vf[Key.QUERY_FUNC]: #query function, excluding prepare
        if vf[Key.QUERY_OPLINE_TYPE] == OplineType.IS_CONST:
            return True
        target_sink = vf_query
        patterns = maybe_safe_sequence[Key.SQLI_SAFESEQ][Key.QUERY_FUNC]
        escape_functions = {'mysqli_real_escape_string', 'mysqli::real_escape_string', 'PDO::quote'}
    elif Key.PREPARE_FUNC in vf and vf[Key.PREPARE_FUNC]:
        patterns = maybe_safe_sequence[Key.SQLI_SAFESEQ][vf_name]
        list_placeholders = utils.get_query_params_placeholders(vf_query)
        target_sink = vf_query  #it is likely the query is not bound or prepare -> execute
        prepare_retval = vf[Key.RETURN_VALUE]
        bind_methods = {'mysqli_stmt::bind_param', 'PDOStatement::bindParam', 'PDOStatement::bindValue'}
        
    trace = c.function_trace
    
    for pattern in patterns:
        pi = 0                  #index in pattern to be matched
        plen = len(pattern)
        num_sanitation = 0
        num_sanitation_in_sink = 0
        for func in trace:
            target = pattern[pi]
            match_prev = False  #to identify repeated function call in sequence, e.g. bindParam, bindValue
            count = 0           #count the number of the same functions with different parameters is called
                                #e.g. bindParam or bindValue
            # Normalize to a tuple so both string and tuple/list in safe seq list are handled
            if isinstance(target, (tuple, list)):
                options = tuple(target)
            else:
                options = (target,)
            
            func_name = func[Key.FUNCTION_NAME]
            if func_name in options:
                #case 1: the function in trace is a sanitation function.
                if(func_name in SANITATION_FUNCTIONS and func_name in escape_functions):
                    tainted_string = func[Key.STRING] #the string to be sanitized
                    params_reach_sink = utils.match_fuzz_params_to_function_param(c.fuzz_params, tainted_string)
                    if not params_reach_sink: #the sanitation function does not use our input, seems bogus
                        continue
                    num_sanitation += 1
                    if num_sanitation == qfunc_num_params_quoted:
                        pi += 1
                    sanitized_inputs.append(func[Key.RETURN_VALUE])
                #case 2: the function in trace matches with vuln function vf
                elif Key.QUERY_FUNC in func and func[Key.FUNCTION_NAME] == vf_name and func[Key.QUERY] == target_sink:
                    for saninput in sanitized_inputs:  
                        if utils.is_payload_in_sink(saninput, target_sink):
                            num_sanitation_in_sink += 1 
                        elif utils.match_fuzz_params_to_function_param(c.fuzz_params, target_sink):
                            num_sanitation_in_sink += 1 
                         
                    if num_sanitation_in_sink == qfunc_num_params_quoted:
                        pi +=1
                    else:
                        continue
                #Prepare function may contain placeholders only, placeholders and variables 
                #which may come from inputs, or only variables which also may come from inputs
                elif (Key.PREPARE_FUNC in func and func[Key.FUNCTION_NAME] == vf_name and 
                      func[Key.RETURN_VALUE] == prepare_retval):
                    if list_placeholders and func[Key.QUERY_OPLINE_TYPE] == OplineType.IS_CONST: #only placeholders
                        pi += 1
                    elif qfunc_num_params_quoted > 0: #contain variable, but all are sanitized
                        for saninput in sanitized_inputs:  
                            if utils.is_payload_in_sink(saninput, target_sink):
                                num_sanitation_in_sink += 1 
                            elif utils.match_fuzz_params_to_function_param(c.fuzz_params, target_sink):
                                num_sanitation_in_sink += 1
                        if num_sanitation_in_sink == qfunc_num_params_quoted:
                            pi +=1     
                        else:
                            continue
                elif Key.BIND_EXECUTE_FUNC in func:
                    if 'bind_param' in func[Key.FUNCTION_NAME] and prepare_retval in func[Key.OBJECT_POINTER]:
                        if utils.match_fuzz_params_to_function_param(c.fuzz_params, func[Key.PARAMETERS]):
                            pi += 1
                    elif func[Key.FUNCTION_NAME] in bind_methods and prepare_retval in func[Key.OBJECT_POINTER]:
                        if utils.match_fuzz_params_to_function_param(c.fuzz_params, func[Key.PARAMETERS]):
                            pi +=1
                    elif 'mysqli_stmt_execute' in func[Key.FUNCTION_NAME] and prepare_retval in func[Key.OBJECT_POINTER]:
                        if 'bind_param' in pattern[pi-1] and func[Key.NUM_PARAMS] == 1:
                            pi +=1
                        elif func[Key.NUM_PARAMS] > 1 and utils.match_fuzz_params_to_function_param(c.fuzz_params, func[Key.PARAMETERS]):
                            pit +=1
                        else:
                            continue
                    elif '::execute' in func[Key.FUNCTION_NAME] and prepare_retval in func[Key.OBJECT_POINTER]:
                        #normalize patterh[pi-1] to a list to allow pattern as single string or a list.
                        if isinstance(pattern[pi-1], str):
                            pattern[pi-1] = [pattern[pi-1]]
                        if (any(method in bind_methods for method in pattern[pi-1]) 
                            and func[Key.NUM_PARAMS] == 0):
                            pi +=1
                        elif func[Key.NUM_PARAMS] > 0 and utils.match_fuzz_params_to_function_param(c.fuzz_params, func[Key.PARAMETERS]):
                            pi +=1
                        else:
                            continue             
            elif func_name not in options and pi > 0:
                prev = pattern[pi-1]
                if isinstance(prev, (tuple, list)):
                    match_prev = func_name in prev
                else:
                    match_prev = func_name == prev

                if match_prev:
                    count+=1 #repeated function call
                    print(func_name)
                else:
                    continue
            else:
                continue

            if pi == plen:
                return True
    return False  # Not all pattern elements were matched

def is_xxe_func_exec_safe(vf, c): 
    key = Key.XML_PAYLOAD
    opline_type_key = Key.SINK_OPLINE_TYPE
    target_sink = vf[key]           #this is the vuln func parameter where fuzz inputs is expected land
    if opline_type_key == OplineType.IS_CONST:
        return True
    trace = c.function_trace
    options = -1
    is_safe = False

    patterns = maybe_safe_sequence[Key.XXE_SAFESEQ]
    for pattern in patterns:
        pi = 0                      #index in pattern to be matched
        plen = len(pattern)
        count = 0
    
        for func in trace:
            target = pattern[pi]
            # Normalize to a tuple so both string and tuple/list in safe seq list are handled
            if isinstance(target, (tuple, list)):
                options = tuple(target)
            else:
                options = (target,)
            
            func_name = func[Key.FUNCTION_NAME]
            if func_name in options:
                #case 1: the function in trace disable entity loader
                if func_name == 'libxml_disable_entity_loader':   
                    if func[Key.DISABLE]:
                        pi += 1
                    else:
                        continue
                #case 2: the function in trace matches with vuln function vf
                elif (func_name == vf[Key.FUNCTION_NAME] and func[key] == target_sink):
                    params_reach_sink = utils.match_fuzz_params_to_function_param(c.fuzz_params, target_sink)
                    if params_reach_sink: 
                        print(func_name)
                        pi +=1
                    else:
                        continue
            elif func_name not in options and pi > 0:
                prev = pattern[pi-1]
                if isinstance(prev, (tuple, list)):
                    match_prev = func_name in prev
                else:
                    match_prev = func_name == prev

                if match_prev:
                    count+=1 #repeated function call
                    print(func_name)
                else:
                    continue
            else:
                continue

            if pi == plen:
                is_safe = True
            
    if is_safe:
        return is_safe
    else:
        options = vf[Key.OPTIONS]
        dangerous = (   (options & AllFlags.LIBXML_DTDLOAD) and 
                        (options & (AllFlags.LIBXML_NOENT | 
                                    AllFlags.LIBXML_DTDVALID |
                                    AllFlags.LIBXML_DTDATTR))
                    ) 
        if not dangerous:
            #if not (DTDLOAD and either noent, valid, or attr) then it is safe
            is_safe = True
    return is_safe

def is_safe_from_tags_based_xss(c, vuln_func=None, sink_key=None, opline_type_key=None, param_key_tup=None): #c is candidate
    if vuln_func:
        func_name = vuln_func[Key.FUNCTION_NAME]
        target_sink = vuln_func[sink_key] 
        #this is the vuln func parameter where fuzz inputs is expected land
        # if opline_type_key in vuln_func and vuln_func[opline_type_key] == OplineType.IS_CONST: #not reliable for prepare func
        #     return True
    trace = c.function_trace
    sanitized_string = None
    patterns = maybe_safe_sequence[Key.XSS_SAFESEQ]   

    for pattern in patterns:
        pi = 0                      #index in pattern to be matched
        plen = len(pattern)
        count = 0
        for func in trace:
            target = pattern[pi]
            #normalize to a tuple so both string and tuple/list in safe seq list are handled
            if isinstance(target, (tuple, list)):
                options = tuple(target)
            else:
                options = (target,)  
            func_name = func[Key.FUNCTION_NAME]
            if func_name in options:
                if func_name in SANITATION_FUNCTIONS:
                    tainted_string = func[Key.STRING] #the string to be sanitized
                    params_reach_sink = utils.match_fuzz_params_to_function_param(c.fuzz_params, tainted_string)
                    if params_reach_sink:
                        if not contains_html_tags(tainted_string):
                            continue
                        else:
                            sanitized_string = func[Key.RETURN_VALUE]
                            if not contains_html_tags(sanitized_string):
                                pi += 1
                        
                #case 2: the function in trace matches with vuln function vf
                elif (func_name == vuln_func[Key.FUNCTION_NAME] and func[sink_key] == target_sink):
                    if sanitized_string:
                        input_matched_sink = utils.is_payload_in_sink(sanitized_string, target_sink) 
                    else:   #the sanit functioon heavily transformed the input, so we check if the fuzzed param is 
                            #used in the sink. In normal case, works, but not when attackers evades.
                        params_reach_sink = utils.match_fuzz_params_to_function_param(c.fuzz_params, target_sink) 
                        input_matched_sink = params_reach_sink
                    if input_matched_sink:
                        # print(func_name)
                        pi +=1
                    else:
                        continue
            elif func_name not in options and pi > 0:
                prev = pattern[pi-1]
                if isinstance(prev, (tuple, list)):
                    match_prev = func_name in prev
                else:
                    match_prev = func_name == prev

                if match_prev:
                    count+=1 #repeated function call
                    # print(func_name)
                else:
                    continue
            else:
                continue
            if pi == plen:
                return True       
    return False  # Not all pattern elements were matched

def contains_html_tags(s):
    return bool(re.search(r'<[^>]+>', s))

def update_vuln_func_status_and_remove(vuln_func, c, status):
    vuln_func[Key.VULN_FUNC_STATUS] = status
    c.vuln_functions_done.append(vuln_func)
    if vuln_func in c.vuln_functions:
        c.vuln_functions.remove(vuln_func)

def query_types(query):
    if query is None:
        return None
    pattern = re.compile(r"\b(insert|select|update|delete|create|drop)\b",flags=re.IGNORECASE)
    query_cleaned = re.sub(r"'(?:''|[^'])*'", "", query) #remove literal, in case lit similar to kw: 'insert' 'update', etc 
    keywords = pattern.findall(query_cleaned)
    q_types = 0
    for kw in keywords:
        if kw.lower() == 'select':
            q_types |= SQLQueryType.SELECT
        elif kw.lower() == 'insert':
            q_types |= SQLQueryType.INSERT
        elif kw.lower() == 'update':
            q_types |= SQLQueryType.UPDATE
        elif kw.lower() == 'delete':
            q_types |= SQLQueryType.DELETE
        elif kw.lower() == 'create':
            q_types |= SQLQueryType.CREATE
        elif kw.lower() == 'drop':
            q_types |= SQLQueryType.DROP    
    return q_types

#If c is the only parameter, it is likely that a matching function (preg_match, fnmatch, etc) prevents the execution
#to reach the sink of vulnerable function.
def sanitation_report(c, vulnerability=None, vuln_func=None, trace_is_updated=False):
    vf_sink_key = None
    report = {}
    if vulnerability and vuln_func:
        if not trace_is_updated:
            c.sanit_functions = update_sanitation_functions(c=c, vuln_type=vulnerability)
        elif trace_is_updated:
            c.sanit_functions = update_sanitation_functions(c=c, trace_is_updated=True, vuln_type=vulnerability)
        if vulnerability == Vulnerability.CODE_EXEC:
            vf_sink_key = Key.COMMAND
        elif vulnerability == Vulnerability.PATHTRAVS:
            vf_sink_key = Key.PATH
        elif vulnerability == Vulnerability.SQLI:
            vf_sink_key = Key.QUERY
        elif vulnerability == Vulnerability.UNSERIALIZE:
            vf_sink_key = Key.SERIALIZED_STRING
        elif vulnerability == Vulnerability.XXE:
            vf_sink_key = Key.XML_PAYLOAD 
    else:
        if not trace_is_updated:
            c.sanit_functions = update_sanitation_functions(c)
        elif trace_is_updated:
            c.sanit_functions = update_sanitation_functions(c, trace_is_updated=True)
    
    if not c.sanit_functions:
        return report
    
    for func in c.sanit_functions:
        sanit_sink = func[Key.STRING]
        for req_part, params in c.fuzz_params.items():
            for param, val in params.items():
                key = (req_part, param)
                # If func is the sanitation function that immediately receive the fuzzing parameters from user or request.
                if str(val) in sanit_sink:
                    if key not in report:
                        row = [func] #first row for the key
                        '''
                        When vuln_func is not None, meaning that we want to collect sanitation sequence that lands in
                        vuln func sink, then check if func is the immediate predecessor of vuln_func. 
                        '''
                        if vf_sink_key is not None:
                            func_retval = func.get(Key.RETURN_VALUE)
                            func_retval_matched = func_retval and str(func_retval) in vuln_func[vf_sink_key]
                            sanit_sink_matched = sanit_sink and str(sanit_sink) in vuln_func[vf_sink_key]
                            if func_retval_matched or sanit_sink_matched: 
                                row.append(vuln_func)
                                report[key] = [row]
                                continue
                        report[key] = [row]
                    else:
                        report[key].extend([[func]])
                        if vf_sink_key is not None:
                            func_retval = func.get(Key.RETURN_VALUE)
                            func_retval_matched = func_retval and str(func_retval) in vuln_func[vf_sink_key]
                            sanit_sink_matched = sanit_sink and str(sanit_sink) in vuln_func[vf_sink_key]
                            if func_retval_matched or sanit_sink_matched: 
                                report[key][-1].append(vuln_func)
                                continue
                                
                # It is possible that func is the succeeding sanitation called after the last one in a chain or sequence 
                # of sanitation. If this is the case, we check the last sanitation function in each row of report, 
                # if its return value is the input to func. If yes, add func to chain.
                elif str(val) not in sanit_sink and key in report:
                    for row in report[key]:
                        last = row[-1]
                        retval = last.get(Key.RETURN_VALUE)
                        retval_matched = retval and str(retval) in sanit_sink
                        sink_matched = (last[Key.FUNCTION_NAME] in (STRING_MATCH_FUNCTIONS | NUMERIC_MATCH_FUNCTIONS 
                                                                    | FILESYSTEM_MATCH_FUNCTIONS) 
                                        and (Key.STRING in last and last[Key.STRING] in sanit_sink))                  
                        if retval_matched or sink_matched:
                            row.append(func)
                            if vf_sink_key is not None:
                                func_retval = func.get(Key.RETURN_VALUE)
                                func_retval_matched = func_retval and str(func_retval) in vuln_func[vf_sink_key]
                                sanit_sink_matched = sanit_sink and str(sanit_sink) in vuln_func[vf_sink_key]
                                if func_retval_matched or sanit_sink_matched:  
                                    row.append(vuln_func)
                                    continue 

    if vf_sink_key and vulnerability and vuln_func:
        for key in list(report.keys()):
            report[key] = [row[:-1] for row in report[key] if row and row[-1] == vuln_func]
            if not report[key]:
                del report[key]
        return report
    else:
        return report

def function_sanitation_report(candidate_sanit_rep, func, sink_key):
    func_sanit_report = {}
    if not candidate_sanit_rep:
        print(f"No sanitation report for Candidate")
        return func_sanit_report
    
    for key in list(candidate_sanit_rep.keys()):
        rows = []
        for row in candidate_sanit_rep[key]:
            last = row[-1]
            last_retval = last.get(Key.RETURN_VALUE)
            last_sanit_sink = last.get(Key.STRING)
            retval_matched = last_retval and str(last_retval) in func[sink_key]
            sanit_sink_matched = last_sanit_sink and str(last_sanit_sink) in func[sink_key]
            if retval_matched or sanit_sink_matched:
                rows.append(row)
        if rows:
            func_sanit_report[key]= rows
    return func_sanit_report

'''
Generate a string to bypass a sequence of sanitation functions. 
String generation is tailored to specific sanitation function
To add more sanitatio aware string generation, add more cases in the for loop below.
'''
def generate_sanitation_aware_string(sanit_seq, payload=None):
    payload_unused = False
    if payload:
        genstr = payload
    else:
        genstr = ''
    # num_applied = 0
    for func in reversed(sanit_seq):
        if func[Key.FUNCTION_NAME] and func[Key.FUNCTION_NAME] in STRING_MATCH_FUNCTIONS:
            pattern = func[Key.PATTERN]
            if 'preg' in func[Key.FUNCTION_NAME]:
                genstr = utils.generate_string_matches_pattern(pattern, MatchingPattern.REGEX, genstr)
            else:
                genstr = utils.generate_string_matches_pattern(pattern, MatchingPattern.STRING_LIT,genstr)
        elif func[Key.FUNCTION_NAME] and func[Key.FUNCTION_NAME] in STRING_REPLACE_FUNCTIONS:
            patterns = func[Key.PATTERN]
            replacements = func[Key.REPLACEMENT]
            # Although php userland function allows input/subject as an array of string, the caller to this function must
            # guarantee only single string is mutated. See the caller has statement like: c.fuzz_params[key[0]][key[1]]
            if not genstr:
                genstr = func[Key.STRING]
            if 'preg' in func[Key.FUNCTION_NAME]:
                genstr = utils.generate_string_bypassing_replacement(patterns, MatchingPattern.REGEX, replacements, genstr)
            else:
                genstr = utils.generate_string_bypassing_replacement(patterns, MatchingPattern.STRING_LIT, replacements, genstr)
        elif func[Key.FUNCTION_NAME] and func[Key.FUNCTION_NAME] in DECODE_FUNCTIONS:
            if not genstr:
                genstr = func[Key.STRING]
            if func[Key.FUNCTION_NAME] == 'base64_decode':
                genstr = base64.b64encode(genstr.encode('utf-8')).decode('utf-8')
            elif func[Key.FUNCTION_NAME] == 'json_decode':
                pass
        elif func[Key.FUNCTION_NAME] and func[Key.FUNCTION_NAME] in NUMERIC_MATCH_FUNCTIONS:
            if func[Key.FUNCTION_NAME] == 'is_numeric':
                if func.get(Key.NEGATED):
                    if genstr and not genstr.isnumeric():
                        pass
                    else:
                        genstr = utils.generate_random_normal_string()
                else:
                    if genstr and str(genstr).isnumeric():  #possible genstr is int or float already
                        pass
                    else:
                        genstr = utils.generate_integer_string()
        elif func[Key.FUNCTION_NAME] and func[Key.FUNCTION_NAME] in FILESYSTEM_MATCH_FUNCTIONS:
            if func[Key.FUNCTION_NAME] in FILESYSTEM_MATCH_FUNCTIONS:
                genstr = '../../../../../../../../../etc/passwd'  
                payload_unused = True
                
    return genstr

