import os
import hashlib
from libzimpaf.constants import (ZendOpcode, Key, ZendDataType, HttpMethod)
import utils

ZEND_IS_INSTRUCTIONS =  {ZendOpcode.ZEND_IS_IDENTICAL, 
                         ZendOpcode.ZEND_IS_NOT_IDENTICAL,
                         ZendOpcode.ZEND_IS_EQUAL,
                         ZendOpcode.ZEND_IS_NOT_EQUAL,
                         ZendOpcode.ZEND_IS_SMALLER,
                         ZendOpcode.ZEND_IS_SMALLER_OR_EQUAL}
ZEND_CASE_INSTRUCTIONS ={ZendOpcode.ZEND_CASE, 
                         ZendOpcode.ZEND_CASE_STRICT}
ZEND_JMP_INSTRUCTIONS = {ZendOpcode.ZEND_JMP, 
                         ZendOpcode.ZEND_JMPZ, 
                         ZendOpcode.ZEND_JMPNZ, 
                         ZendOpcode.ZEND_JMPZ_EX, 
                         ZendOpcode.ZEND_JMPNZ_EX,
                         ZendOpcode.ZEND_JMP_NULL }

'''
To filter input parameters in conditional statement using ZEND_JMP*, ZEND_IS_*, and ZEND_CASE*
ZEND_JMP* does not have operand 2, while ZEND_IS_* and ZEND_CASE* have operand 2.
When Input parameter is used in multiple ZEND_JMP*, only 1 trace is collected because it represents them.
However, if input parameter is used in ZEND_IS_* or ZEND_CASE, all distinct traces are collected.
'''
def params_comparisons_report(c):
    params_cmps_path = os.path.join("/shared-tmpfs/input_params_comparisons",f"{c.coverage_id}.json")
    params_traces = utils.load_json_file(params_cmps_path)
    if not params_traces:
        return None
    param_cmp_report = []
    key_jmp_seen = set()
    key_is_case_seen_hash = set()
    for t in params_traces:
        if t.get(Key.OPCODE) in ZEND_IS_INSTRUCTIONS or t.get(Key.OPCODE) in ZEND_CASE_INSTRUCTIONS:
            hash_str = (f"{t[Key.OP1_INPUT_PARAM]}_{t[Key.OP2_INPUT_PARAM]}_{t[Key.OPCODE]}_"
                        f"{t[Key.FILENAME]}_{t[Key.LINENO]}")
            h = hashlib.sha256(hash_str.encode('utf-8')).hexdigest()
            if h in key_is_case_seen_hash:
                continue
            else:
                key_is_case_seen_hash.add(h)
                param_cmp_report.append(t)
        elif t.get(Key.OPCODE) in ZEND_JMP_INSTRUCTIONS:
            if t[Key.OP1_INPUT_PARAM] in key_jmp_seen:
                continue
            else:
                key_jmp_seen.add(t[Key.OP1_INPUT_PARAM])
                param_cmp_report.append(t)
    return param_cmp_report

'''
The mutation used to find new path by flipping the value.
Called by fuzz_candidate_with_params_comparisons(...)
It supports flipping one branch only for now, consider to allow multiple branches 
(param_cmp_rep is a list)
'''
    
def param_comparison_mutation_flipping(c, param_cmp_rep): 
    if c.http_method.upper() == HttpMethod.POST:
        out_key = Key.BODY_PARAMS
    elif c.http_method.upper() == HttpMethod.GET:
        out_key = Key.QUERY_PARAMS
    
    opcode = param_cmp_rep.get(Key.OPCODE)
    result = param_cmp_rep.get(Key.RESULT)

    EQUALITY_OPCODES = {ZendOpcode.ZEND_IS_IDENTICAL, ZendOpcode.ZEND_IS_EQUAL} | ZEND_CASE_INSTRUCTIONS

    if opcode in EQUALITY_OPCODES:
        flipping_equality_inequality_branching(param_cmp_rep, c, out_key, make_equal=(result == 0))

    elif opcode in (ZendOpcode.ZEND_IS_NOT_IDENTICAL, ZendOpcode.ZEND_IS_NOT_EQUAL):
        flipping_equality_inequality_branching(param_cmp_rep, c, out_key, make_equal=(result == 1))
    
    elif opcode in (ZendOpcode.ZEND_IS_SMALLER, ZendOpcode.ZEND_IS_SMALLER_OR_EQUAL):
        flipping_smaller_smallerequal_branching(param_cmp_rep, c, out_key, opcode, make_s_se=(result == 1))
       
    elif opcode in ZEND_JMP_INSTRUCTIONS:
        flipping_jump_branching(param_cmp_rep, c, out_key, is_taken = (result == 1))

def param_comparison_mutation_preserving(c, param_cmp_rep): 
    if c.http_method.upper() == HttpMethod.POST:
        out_key = Key.BODY_PARAMS
    elif c.http_method.upper() == HttpMethod.GET:
        out_key = Key.QUERY
    
    opcode = param_cmp_rep.get(Key.OPCODE)
    result = param_cmp_rep.get(Key.RESULT)
    EQUALITY_OPCODES = {ZendOpcode.ZEND_IS_IDENTICAL, ZendOpcode.ZEND_IS_EQUAL} | ZEND_CASE_INSTRUCTIONS

    if opcode in EQUALITY_OPCODES:
        flipping_equality_inequality_branching(param_cmp_rep, c, out_key, make_equal=(result == 1))

    elif opcode in (ZendOpcode.ZEND_IS_NOT_IDENTICAL, ZendOpcode.ZEND_IS_NOT_EQUAL):
        flipping_equality_inequality_branching(param_cmp_rep, c, out_key, make_equal=(result == 0))
    
    elif opcode in (ZendOpcode.ZEND_IS_SMALLER, ZendOpcode.ZEND_IS_SMALLER_OR_EQUAL):
        flipping_smaller_smallerequal_branching(param_cmp_rep, c, out_key, opcode, make_s_se=(result == 0))
       
    elif opcode in ZEND_JMP_INSTRUCTIONS:
        flipping_jump_branching(param_cmp_rep, c, out_key, is_taken = (result == 0))
       

def flipping_equality_inequality_branching(param_cmp_rep, c, out_key, make_equal: bool):
    key_in_op1 = param_cmp_rep.get(Key.OP1_INPUT_PARAM)
    key_in_op2 = param_cmp_rep.get(Key.OP2_INPUT_PARAM)
    if make_equal:
        # force equality / identity
        if param_cmp_rep.get(Key.OP1_INPUT_PARAM) and param_cmp_rep.get(Key.OP2_INPUT_PARAM):
            value = utils.generate_integer_string()
            c.fuzz_params[out_key][key_in_op1] = value
            c.fuzz_params[out_key][key_in_op2] = value

        elif param_cmp_rep.get(Key.OP1_INPUT_PARAM):
            c.fuzz_params[out_key][key_in_op1] = param_cmp_rep.get(Key.OP2_VALUE)

        elif param_cmp_rep.get(Key.OP2_INPUT_PARAM):
            c.fuzz_params[out_key][key_in_op2] = param_cmp_rep.get(Key.OP1_VALUE)

    else:
        # force inequality / non-identity
        if param_cmp_rep.get(Key.OP1_INPUT_PARAM) and param_cmp_rep.get(Key.OP2_INPUT_PARAM):
            c.fuzz_params[out_key][key_in_op1] = utils.generate_integer_string()
            c.fuzz_params[out_key][key_in_op2] = utils.generate_integer_string()

        elif param_cmp_rep.get(Key.OP1_INPUT_PARAM):
            data_type = param_cmp_rep.get(Key.OP2_DATA_TYPE)
            if data_type in [ZendDataType.IS_STRING,
                             ZendDataType.IS_LONG,
                             ZendDataType.IS_DOUBLE]:
                c.fuzz_params[out_key][key_in_op1] = generate_val_using_zend_type(data_type)
            elif data_type == ZendDataType.IS_TRUE:
                c.fuzz_params[out_key][key_in_op1] = 0
            elif data_type == ZendDataType.IS_FALSE:
                c.fuzz_params[out_key][key_in_op1] = 1

        elif param_cmp_rep.get(Key.OP2_INPUT_PARAM):
            data_type = param_cmp_rep.get(Key.OP1_DATA_TYPE)
            if data_type in [ZendDataType.IS_STRING,
                             ZendDataType.IS_LONG,
                             ZendDataType.IS_DOUBLE]:
                c.fuzz_params[out_key][Key.OP2_INPUT_PARAM] = generate_val_using_zend_type(data_type)
            elif data_type == ZendDataType.IS_TRUE:
                c.fuzz_params[out_key][key_in_op2] = 0
            elif data_type == ZendDataType.IS_FALSE:
                c.fuzz_params[out_key][key_in_op2] = 1

def flipping_smaller_smallerequal_branching(param_cmp_rep, c, out_key, opcode, make_s_se: bool):
    key_in_op1 = param_cmp_rep.get(Key.OP1_INPUT_PARAM)
    key_in_op2 = param_cmp_rep.get(Key.OP2_INPUT_PARAM)
    if make_s_se == 1:
        if param_cmp_rep.get(Key.OP1_INPUT_PARAM) and param_cmp_rep.get(Key.OP2_INPUT_PARAM):
            data_type = param_cmp_rep.get(Key.OP1_DATA_TYPE)
            if data_type in [ZendDataType.IS_LONG, ZendDataType.IS_DOUBLE]:
                # make OP1 >= OP2 to flip RESULT
                op2_val = generate_val_using_zend_type(data_type)
                op1_val = op2_val + 1 if opcode == ZendOpcode.ZEND_IS_SMALLER else op2_val
                c.fuzz_params[out_key][key_in_op1] = op1_val
                c.fuzz_params[out_key][key_in_op2] = op2_val
            elif data_type == ZendDataType.IS_STRING:
                # lexicographically larger
                op2_val = generate_val_using_zend_type(ZendDataType.IS_STRING)
                op1_val = op2_val + "a"
                c.fuzz_params[out_key][key_in_op1] = op1_val
                c.fuzz_params[out_key][key_in_op2] = op2_val

        elif param_cmp_rep.get(Key.OP1_INPUT_PARAM):
            # Only OP1 controllable → make it >= OP2
            op2_val = param_cmp_rep.get(Key.OP2_VALUE)
            op1_val = op2_val + 1 if opcode == ZendOpcode.ZEND_IS_SMALLER else op2_val
            c.fuzz_params[out_key][key_in_op1] = op1_val

        elif param_cmp_rep.get(Key.OP2_INPUT_PARAM):
            # Only OP2 controllable → make it <= OP1
            op1_val = param_cmp_rep.get(Key.OP1_VALUE)
            op2_val = op1_val - 1 if opcode == ZendOpcode.ZEND_IS_SMALLER else op1_val
            c.fuzz_params[out_key][key_in_op2] = op2_val

    # RESULT == 0 → currently false → generate true to explore path
    else:
        if param_cmp_rep.get(Key.OP1_INPUT_PARAM) and param_cmp_rep.get(Key.OP2_INPUT_PARAM):
            data_type = param_cmp_rep.get(Key.OP1_DATA_TYPE)
            if data_type in [ZendDataType.IS_LONG, ZendDataType.IS_DOUBLE]:
                op1_val = generate_val_using_zend_type(data_type)
                op2_val = op1_val + 1 if opcode == ZendOpcode.ZEND_IS_SMALLER else op1_val
                c.fuzz_params[out_key][key_in_op1] = op1_val
                c.fuzz_params[out_key][key_in_op2] = op2_val
            elif data_type == ZendDataType.IS_STRING:
                op1_val = generate_val_using_zend_type(ZendDataType.IS_STRING)
                op2_val = op1_val + "a"
                c.fuzz_params[out_key][key_in_op1] = op1_val
                c.fuzz_params[out_key][key_in_op2] = op2_val

        elif param_cmp_rep.get(Key.OP1_INPUT_PARAM):
            op2_val = param_cmp_rep.get(Key.OP2_VALUE)
            op1_val = op2_val - 1 if opcode == ZendOpcode.ZEND_IS_SMALLER else op2_val
            c.fuzz_params[out_key][key_in_op1] = op1_val

        elif param_cmp_rep.get(Key.OP2_INPUT_PARAM):
            op1_val = param_cmp_rep.get(Key.OP1_VALUE)
            op2_val = op1_val + 1 if opcode == ZendOpcode.ZEND_IS_SMALLER else op1_val
            c.fuzz_params[out_key][key_in_op2] = op2_val

def flipping_jump_branching(param_cmp_rep, c, out_key, is_taken: bool):
    key_in_op1 = param_cmp_rep.get(Key.OP1_INPUT_PARAM)
    if is_taken == 1:
        if out_key in c.fuzz_params and key_in_op1 in c.fuzz_params[out_key]:
            if isinstance(c.fuzz_params[out_key][key_in_op1],str):
                c.fuzz_params[out_key][key_in_op1] = ""
            elif isinstance(c.fuzz_params[out_key][key_in_op1],(int,float,bool)):
                c.fuzz_params[out_key][key_in_op1] = 0
    else:
        if out_key in c.fuzz_params and key_in_op1 in c.fuzz_params[out_key]:
            if isinstance(c.fuzz_params[out_key][key_in_op1],str):
                c.fuzz_params[out_key][key_in_op1] = utils.generate_random_normal_string()
            elif isinstance(c.fuzz_params[out_key][key_in_op1],(int,float)):
                c.fuzz_params[out_key][key_in_op1] = utils.generate_random_int()
            elif isinstance(c.fuzz_params[out_key][key_in_op1],bool):
                c.fuzz_params[out_key][key_in_op1] = 1

def generate_val_using_zend_type(zend_type):
    if zend_type == ZendDataType.IS_STRING:
        return utils.generate_random_normal_string()
    if zend_type in (ZendDataType.IS_LONG, ZendDataType.IS_DOUBLE, ZendDataType.IS_DOUBLE):
        return utils.generate_random_int()

