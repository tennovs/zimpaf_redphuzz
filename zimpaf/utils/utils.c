/*
 * Copyright © 2026 Tennov Simanjuntak, The University of Texas at Arlington
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 */

#include "../include/utils.h"
#include "../php_zimpaf.h"

#define PHP_STREAM_FLAG_DEAD 0x04

char *concatenate_zval_array_into_string(zval *array_zv) {
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
		ZEND_TSRMLS_CACHE_UPDATE();
	#endif

    if (Z_TYPE_P(array_zv) != IS_ARRAY) {
        return estrdup("");  // Safe fallback
    }

    zend_string *result = NULL;

    ZEND_HASH_FOREACH_VAL(Z_ARRVAL_P(array_zv), zval *val) {
        if (Z_TYPE_P(val) == IS_FALSE) {
            continue; // skip invalid entries
        }

        zval tmp;
        ZVAL_UNDEF(&tmp);
        ZVAL_COPY(&tmp, val);
        convert_to_string(&tmp); // safe conversion

        if (result == NULL) {
            // result = zend_string_copy(Z_STR(tmp)); //increasing tmp
            result = zend_string_init(Z_STRVAL(tmp), Z_STRLEN(tmp), 0);
        } else {
            zend_string *joined = strpprintf(0, "%s_%s", ZSTR_VAL(result), Z_STRVAL(tmp));
            zend_string_release(result);
            result = joined;
        }

        zval_ptr_dtor(&tmp); //clean up with garbage collectio enabled.
    } ZEND_HASH_FOREACH_END();

    if (result) {
        char *cstr = estrdup(ZSTR_VAL(result)); // caller must efree
        zend_string_release(result);
        return cstr;
    } else {
        return estrdup(""); 
    }
}

char *get_input_superglobal(zval *type_zv) {
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
		ZEND_TSRMLS_CACHE_UPDATE();
	#endif

    if (Z_TYPE_P(type_zv) != IS_LONG) {
        return estrdup("");  
    }
    int input_type = Z_LVAL_P(type_zv);
    zval *input_array = NULL;
    switch (input_type) {
        case 0: // INPUT_POST
            zend_is_auto_global_str(ZEND_STRL("_POST"));
            input_array = &PG(http_globals)[TRACK_VARS_POST];
            break;
        case 1: // INPUT_GET
            zend_is_auto_global_str(ZEND_STRL("_GET"));
            input_array = &PG(http_globals)[TRACK_VARS_GET];
            break;
        case 2: // INPUT_COOKIE
            zend_is_auto_global_str(ZEND_STRL("_COOKIE"));
            input_array = &PG(http_globals)[TRACK_VARS_COOKIE];
            break;
         case 4: // INPUT_ENV
            zend_is_auto_global_str(ZEND_STRL("_ENV"));
            input_array = &PG(http_globals)[TRACK_VARS_ENV];
            break;
        case 5: // INPUT_SERVER
            zend_is_auto_global_str(ZEND_STRL("_SERVER"));
            input_array = &PG(http_globals)[TRACK_VARS_SERVER];
            break;
        default:
            return estrdup("");  
    }
    if (input_array && Z_TYPE_P(input_array) == IS_ARRAY) {
        return concatenate_zval_array_into_string(input_array);
    } else {
        return estrdup("");  
    }
}

unsigned is_op_originates_from_const(uint32_t var_num, zend_op * opl, const zend_op_array *op_array) {
	#if defined(ZTS) && defined(COMPILE_DL_TEST)
		ZEND_TSRMLS_CACHE_UPDATE();
	#endif
	
	if (opl <= op_array->opcodes) {
		return 0;
	} else{
		zend_op *prev_opline = opl - 1;
		if (EX_VAR_TO_NUM(prev_opline->result.var) == var_num){
			switch (prev_opline->opcode) {
				case ZEND_FETCH_CONSTANT:
                case ZEND_FETCH_CLASS_CONSTANT:
                case ZEND_DECLARE_CONST:
					return IS_CONST; // IS_CONST = 1, The variable is assigned a constant value
				case ZEND_ASSIGN:
				case ZEND_QM_ASSIGN:
                case ZEND_ROPE_INIT:
					if (prev_opline->op2_type == IS_CONST)  {
						return 1; // The variable is assigned a constant value
					} else if (prev_opline->op2_type == IS_TMP_VAR || prev_opline->op2_type == IS_VAR 
																|| prev_opline->op2_type == IS_CV) {
						return is_op_originates_from_const(EX_VAR_TO_NUM(prev_opline->op2.var), prev_opline, op_array);
					}
				case ZEND_FAST_CONCAT:
				case ZEND_CONCAT: 
                case ZEND_ROPE_ADD:
                case ZEND_ROPE_END:{
                    int op1_const = (prev_opline->op1_type == IS_CONST);
                    int op2_const = (prev_opline->op2_type == IS_CONST);

                    if (!op1_const && (prev_opline->op1_type == IS_TMP_VAR 
                                    || prev_opline->op1_type == IS_VAR 
                                    || prev_opline->op1_type == IS_CV)) {
                        op1_const = is_op_originates_from_const(EX_VAR_TO_NUM(prev_opline->op1.var), prev_opline, op_array);
                    }

                    if (!op2_const && (prev_opline->op2_type == IS_TMP_VAR 
                                        || prev_opline->op2_type == IS_VAR 
                                        || prev_opline->op2_type == IS_CV)) {
                        op2_const = is_op_originates_from_const(EX_VAR_TO_NUM(prev_opline->op2.var), prev_opline, op_array);
                    }
                    if(op1_const == IS_CONST && op2_const == IS_CONST){
                        return 1;
                    }else{
                        return 0;
                    }  
                }
                case ZEND_EXT_STMT:
                    return is_op_originates_from_const(var_num, prev_opline, op_array);	
                case ZEND_FETCH_R:
                case ZEND_FETCH_DIM_R:
                    return 0;
                default:
                    return 0;
			}	
        //does not store result									
		}else if(prev_opline->result.var == (uint32_t)-1 && EX_VAR_TO_NUM(prev_opline->op1.var) == var_num){
            if(prev_opline->op2_type == IS_TMP_VAR || prev_opline->op2_type == IS_VAR 
                                                    || prev_opline->op2_type == IS_CV){
                return is_op_originates_from_const(EX_VAR_TO_NUM(prev_opline->op2.var), prev_opline, op_array);	
            }else if(prev_opline->op2_type == IS_CONST){
                return 1;
            }else{
                return 0;
            }
        }else{  //if (EX_VAR_TO_NUM(prev_opline->result.var) != var_num){
			return is_op_originates_from_const(var_num, prev_opline, op_array);	
		}					
	}
}

int is_func_param_string_literal(zval *param, zend_execute_data *execute_data){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
		ZEND_TSRMLS_CACHE_UPDATE();
	#endif

    unsigned int is_literal = 0; 
    if (Z_TYPE_P(param) != IS_STRING) {
        return 0;
    }
    zend_string *str = Z_STR_P(param);
    zend_execute_data *prev = execute_data->prev_execute_data;
    if (prev == NULL){
        return 0;
    }
    zend_op_array *op_array = &prev->func->op_array;

    for (uint32_t i = 0; i < op_array->last_literal; i++) {
        if (op_array->literals[i].value.str == str) {
            return IS_CONST; //return 1
        }
    }

    zend_op *call_opline = (zend_op *) prev->opline;
    zend_op *last_op = op_array->opcodes + op_array->last - 1;
    for(zend_op *opl = call_opline; opl >= op_array->opcodes; opl--){
        switch (opl->opcode) {
            case ZEND_SEND_VAR:
            case ZEND_SEND_VAR_EX:
            case ZEND_SEND_REF:{
                if(EX_VAR(opl->result.var) == param){
                    return is_op_originates_from_const(EX_VAR_TO_NUM(opl->op1.var), opl, op_array);
                }else if(EX_VAR(opl->op1.var) == param){
                     return is_op_originates_from_const(EX_VAR_TO_NUM(opl->op1.var), opl, op_array);
                }
            }
            case ZEND_SEND_VAL:
            case ZEND_SEND_VAL_EX:
                if(opl->op1.var == IS_CONST){
                    return IS_CONST;
                }else {
                    return is_op_originates_from_const(EX_VAR_TO_NUM(opl->op1.var), opl, op_array);
                }     
        }									  
    }
    return is_literal;           // query is runtime-generated, not sure if it is literal
}

char *get_return_value_string(zval *return_value){
    zval *retval = return_value;
    while (Z_TYPE_P(retval) == IS_REFERENCE) {
        retval = Z_REFVAL_P(retval);
    }
    zend_string *param_str;
    if (Z_TYPE_P(retval) == IS_FALSE){
        param_str = zend_strpprintf(0, "%s", "");
    }else if(Z_TYPE_P(retval ) == IS_OBJECT || Z_TYPE_P(retval) == IS_RESOURCE || Z_TYPE_P(retval ) == IS_ARRAY){
        switch (Z_TYPE_P(retval )) {
            case IS_OBJECT: {
                // Use class name as a stand-in
                zend_object *obj = Z_OBJ_P(retval);
                param_str = zend_strpprintf(0, "%p", obj);          //print the address of object
                break;
            }
            case IS_ARRAY:
                zend_array *arr = Z_ARRVAL_P(retval);
                param_str = zend_strpprintf(0, "%p", (void *)arr);  //print the address of array
                break;
            case IS_RESOURCE:
                zend_resource *res = Z_RES_P(retval);               
                param_str = zend_strpprintf(0, "%p", (void *)res);  //print the address of resource
                break;
            // default:
            //     param_str = zend_string_init("[unknown]", sizeof("[unknown]") - 1, 0);
            //     break;
        }
    }else if (Z_TYPE_P(retval) == IS_NULL){
        param_str = zend_strpprintf(0, "%s", "null");
    }else{
        param_str = zval_get_string(retval);
    }
    size_t len = ZSTR_LEN(param_str);
    char *retval_str = emalloc(len + 1); 
    memcpy(retval_str, ZSTR_VAL(param_str), len);
    retval_str[len] = '\0';
    zend_string_release(param_str);
    return retval_str;
}

char *get_func_param_string(zval *param_zv) {
    if (EXPECTED(Z_TYPE_P(param_zv) == IS_STRING)) {
        return estrdup(Z_STRVAL_P(param_zv));               //caller must efree
    }
    zend_string *tmp = NULL;
    zend_string *str = zval_get_tmp_string(param_zv, &tmp); //allocate temporary string if needed

    if (UNEXPECTED(!str)) {
        return estrdup(""); 
    }
    char *result = estrdup(ZSTR_VAL(str));
    zend_tmp_string_release(tmp);   // clean up temporary string
    return result;                  //caller must efree
}

//extract function from error message
char *extract_function_name(const char *msg){
    char *func;

    if (!msg) {
        return NULL;
    }

    //get from stack trace first
    func = extract_from_stack_trace(msg);
    if (func) {
        return func;
    }

    //fallback to message parsings
    return extract_from_message(msg);
}

//Extract function name from stack trace.
//Sample pattern (cutted): n#0 /var/www/html/testsuite/exception3.php(6): myexception()
char *extract_from_stack_trace(const char *msg){
    const char *p;
    const char *colon;
    const char *name_start;
    const char *open_paren;
    size_t len;

    //find the firs separator "#0 "
    p = strstr(msg, "#0 ");
    if (!p) {
        return NULL;
    }

    //find ": function(" 
    colon = strchr(p, ':');
    if (!colon) {
        return NULL;
    }

    name_start = colon + 1;
    while (*name_start && isspace((unsigned char)*name_start)) {
        name_start++;
    }
    open_paren = strchr(name_start, '(');
    if (!open_paren) {
        return NULL;
    }
    len = open_paren - name_start;
    if (len == 0) {
        return NULL;
    }
    char *func = emalloc(len + 1);
    memcpy(func, name_start, len);
    func[len] = '\0';

    //normalize method name
    normalize_method_name(func);

    return func;
}

//Extract function name from non stack trace message.
//Sample patterns: "include(doesnotexist.php): Failed to open stream: No such file or directory"
char *extract_from_message(const char *msg){
    if (!msg) return NULL;

    //step 1: find ')'
    const char *p = strchr(msg, ')'); 
    if (!p) return NULL;

    //step 2: find '(' that corresponds to this ')'
    const char *open_paren = p;
    while (open_paren > msg && *open_paren != '(') {
        open_paren--;
    }
    if (*open_paren != '(') return NULL;

    //step 3: scan backward to find start of function name
    const char *name_end = open_paren;
    const char *name_start = open_paren;
    while (name_start > msg) {
        char c = *(name_start - 1);
        if (isalnum((unsigned char)c) || c == '_') {
            name_start--;
        } else {
            break;
        }
    }

    //step 4: sanity check
    if (name_start == name_end) return NULL;

    //step 5: allocate and copy
    size_t len = name_end - name_start;
    char *func_name = (char *)emalloc(len + 1); // or emalloc in PHP
    if (!func_name) return NULL;

    memcpy(func_name, name_start, len);
    func_name[len] = '\0';

    //normalize method name
    normalize_method_name(func_name);

    return func_name;
}

#include <string.h>

void normalize_method_name(char *func_name) {
    char *pos;
    if (!func_name) return;

    while ((pos = strstr(func_name, "->")) != NULL) {
        pos[0] = ':'; // replace '-'
        pos[1] = ':'; // replace '>'
        func_name = pos + 2; // continue scanning after this replacement
    }
}

zend_string *is_zval_in_superglobal(zval *zv, uint8_t opcode){
     #if defined(ZTS) && defined(COMPILE_DL_TEST)
		ZEND_TSRMLS_CACHE_UPDATE();
	#endif

    zval *server = &PG(http_globals)[TRACK_VARS_SERVER]; //$_SERVER[string_var]
    zval *req_method = zend_hash_str_find(Z_ARRVAL_P(server), "REQUEST_METHOD", sizeof("REQUEST_METHOD")-1);
    zval *input_array = NULL;

    if(req_method && Z_TYPE_P(req_method) == IS_STRING){
        if (zend_string_equals_literal(Z_STR_P(req_method), "POST")) {
            zend_is_auto_global_str(ZEND_STRL("_POST"));
            input_array = &PG(http_globals)[TRACK_VARS_POST];
        } else if (zend_string_equals_literal(Z_STR_P(req_method), "GET")) {
            zend_is_auto_global_str(ZEND_STRL("_GET"));
            input_array = &PG(http_globals)[TRACK_VARS_GET];
         }
    }
    return is_zval_value_in_array(zv, input_array, opcode);

}

zend_string *is_zval_value_in_array(zval *zv, zval *array_zv, uint8_t opcode){
    if (!zv || !array_zv || Z_TYPE_P(array_zv) != IS_ARRAY) {
        return 0;
    }
    zval res;
    ZVAL_UNDEF(&res);
    zend_ulong h = 0;
    zend_string *key = NULL;
    zval *val = NULL;
    void *ptr_zv = zv->value.ptr;
    printf("Op1 Type: %d, Ptr: %p\n", Z_TYPE_P(zv), zv->value.ptr);

    ZEND_HASH_FOREACH_KEY_VAL(Z_ARRVAL_P(array_zv), h, key, val) {
        if(opcode == ZEND_JMP){
            return NULL;
        }else if (opcode == ZEND_JMPZ     || opcode == ZEND_JMPNZ ||
                  opcode == ZEND_JMPZ_EX  || opcode == ZEND_JMPNZ_EX || opcode == ZEND_JMP_NULL){
            if(compare_function(&res, zv, val) == SUCCESS){
                if(Z_LVAL(res) == 0){
                    return key;
                }
            }
               
        }else{
            if(zv->value.ptr == val->value.ptr){
                printf("Op2 Type: %d, Ptr: %p\n", Z_TYPE_P(val), val->value.ptr);
                return key;
            } 
        }
    } ZEND_HASH_FOREACH_END();
    return NULL;
}


void log_request_param_comparison(zend_string *op1_input_param, zend_string *op2_input_param, 
                                  int result, zval *op1, uint8_t opcode, zval *op2,
                                  char *filename, uint32_t lineno){
    unsigned int len_op1_iprm = op1_input_param ? strlen(ZSTR_VAL(op1_input_param)) : 0;
    unsigned int len_op2_iprm = op2_input_param ? strlen(ZSTR_VAL(op2_input_param)) : 0;
    unsigned int len_opcode = 3;
    unsigned int len_filename = filename ? strlen(filename) : 0;
    unsigned int len_lineno = 5;
    unsigned int len_string_to_hash = len_op1_iprm + len_op2_iprm + len_opcode + len_filename + len_lineno + 1;
    
	char string_to_hash[len_string_to_hash];
    memset(string_to_hash, 0, len_string_to_hash);
	snprintf(string_to_hash, len_string_to_hash, "%s_%s_%u_%s_%u",  op1_input_param ? ZSTR_VAL(op1_input_param) : "",
                                                                    op2_input_param ? ZSTR_VAL(op2_input_param) : "",
                                                                    opcode, filename,lineno);
	zend_ulong current_hash = zend_inline_hash_func(string_to_hash, strlen(string_to_hash));
	zend_ulong x = ZIMPAF_G(last_input_cmp_hash); //just to check the value

	if(current_hash == ZIMPAF_G(last_input_cmp_hash)) {
		return;
	}
    ZIMPAF_G(last_input_cmp_hash) = current_hash;
    zend_ulong y = ZIMPAF_G(last_input_cmp_hash);
    
    cJSON *comparison = cJSON_CreateObject();
	cJSON_AddStringToObject(comparison, "op1_input_param",(op1_input_param && ZSTR_VAL(op1_input_param)) ? 
																ZSTR_VAL(op1_input_param) : "");
	cJSON_AddStringToObject(comparison, "op2_input_param",(op2_input_param && ZSTR_VAL(op2_input_param)) ? 
														   ZSTR_VAL(op2_input_param) : "");
    cJSON_AddNumberToObject(comparison, "result", result);
    if(op1){
        add_zval_value_info_to_cJSON_object(op1, comparison, "op1_value", "op1_data_type");
    }
    cJSON_AddNumberToObject(comparison, "opcode", opcode);
    if (op2){
    add_zval_value_info_to_cJSON_object(op2, comparison, "op2_value", "op2_data_type");
    }
    cJSON_AddStringToObject(comparison, "filename", filename);
    cJSON_AddNumberToObject(comparison, "lineno", lineno);
    cJSON_AddItemToArray(ZIMPAF_G(input_comparisons), comparison);
}

void add_zval_value_info_to_cJSON_object(zval *zv, cJSON *object, char *key_value, char *key_data_type){
    switch (Z_TYPE_P(zv)) {
        case IS_STRING:
            cJSON_AddStringToObject(object, key_value, Z_STRVAL_P(zv));
            cJSON_AddNumberToObject(object, key_data_type, IS_STRING);
            break;
        case IS_LONG:
            cJSON_AddNumberToObject(object, key_value, (double)Z_LVAL_P(zv));
            cJSON_AddNumberToObject(object, key_data_type, IS_LONG);
            break;
        case IS_DOUBLE:
            cJSON_AddNumberToObject(object, key_value, Z_DVAL_P(zv));
            cJSON_AddNumberToObject(object, key_data_type, IS_DOUBLE);
            break;
        case IS_ARRAY:
            char *array_data = concatenate_zval_array_into_string(zv);
            cJSON_AddStringToObject(object, key_value, array_data);
            if(array_data){
                efree(array_data);
            }
            cJSON_AddNumberToObject(object, key_data_type, IS_ARRAY);
            break;
        case IS_FALSE:
            cJSON_AddBoolToObject(object, key_value, 0);
            cJSON_AddNumberToObject(object, key_data_type, IS_FALSE);
            break;
        case IS_TRUE:
            cJSON_AddBoolToObject(object, key_value, 1);
            cJSON_AddNumberToObject(object, key_data_type, IS_TRUE);
            break;
         case IS_NULL:
            cJSON_AddNullToObject(object, key_value);
            cJSON_AddNumberToObject(object, key_data_type, IS_NULL);
            break;
        default:
            cJSON_AddStringToObject(object, key_value, "[OTHER_VAL]");
            cJSON_AddStringToObject(object, key_data_type, "[OTHER_TYPE]");
            break;
    }
}
	





