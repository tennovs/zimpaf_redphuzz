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



#include "../include/sanithook.h"
#include "../include/utils.h"
#include "../php_zimpaf.h"

zif_handler ori_htmlspecialchars_handler = NULL;                    //htmlspecialchars
zif_handler ori_htmlentities_handler = NULL;                        //htmlentities
zif_handler ori_addslashes_handler = NULL;                          //addslashes
zif_handler ori_stripslashes_handler = NULL;                        //stripslashes
zif_handler ori_strip_tags_handler = NULL;                          //strip_tags
zif_handler ori_mysqli_real_escape_string_handler = NULL;           //mysqli_real_escape_string
zif_handler ori_mysqli_real_escape_string_method_handler = NULL;    //mysqli::real_escape_string
zif_handler ori_pdo_quote_method_handler = NULL;                    //PDO::quote
zif_handler ori_preg_replace_handler = NULL;                        //preg_replace  
zif_handler ori_preg_match_handler = NULL;                          //preg_match    
zif_handler ori_realpath_handler = NULL;                            //realpath
zif_handler ori_basename_handler = NULL;                             //basename
zif_handler ori_escapeshellarg_handler = NULL;                      //escapeshellarg
zif_handler ori_escapeshellcmd_handler = NULL;                      //escapeshellcmd
zif_handler ori_str_replace_handler = NULL;                         //str_replace
zif_handler ori_strpos_handler = NULL;                              //strpos
zif_handler ori_stripos_handler = NULL;                             //stripos
zif_handler ori_filter_var_handler = NULL;                          //filter_var
zif_handler ori_filter_var_array_handler = NULL;                    //filter_var_array
zif_handler ori_filter_input_handler = NULL;                        //filter_input
zif_handler ori_filter_input_array_handler = NULL;                  //filter_input_array
zif_handler ori_libxml_disable_entity_loader_handler = NULL;        //libxml_disable_entity_loader
zif_handler ori_libxml_set_external_entity_loader_handler = NULL;   //libxml_set_external_entity_loader
zif_handler ori_is_numeric_handler = NULL;
zif_handler ori_base64_decode_handler = NULL;
zif_handler ori_json_decode_handler = NULL;
zif_handler ori_fnmatch_handler = NULL;                             //fnmatch
zif_handler ori_is_file_handler = NULL;                             //is_file

//added during evaluation with http://testsuite benchmark
zif_handler ori_file_exists_handler = NULL;          //payload in 1st arg
zif_handler ori_is_dir_handler = NULL;         //payload in 1st arg
zif_handler ori_is_executable_handler = NULL;  //payload in 1st arg    
zif_handler ori_is_link_handler = NULL;        //payload in 1st arg
zif_handler ori_is_readable_handler = NULL;    //payload in 1st arg
zif_handler ori_is_writable_handler = NULL;    //payload in 1st arg
zif_handler ori_is_uploaded_file_handler = NULL; //payload in 1st arg
zif_handler get_sanitfunction_handler(char *scope, char *func_name);

zif_handler get_sanitfunction_handler(char *scope_name, char *func_name){
    if(scope_name && func_name){
        if(strcmp(scope_name,"mysqli")== 0){
            if(strcmp(func_name,"real_escape_string")==0){
                return ori_mysqli_real_escape_string_method_handler;
            }
        }else if(strcmp(scope_name,"PDO")==0){
            if(strcmp(func_name,"quote")==0){
                return ori_pdo_quote_method_handler; 
            }
        }
    }else if(strcmp(func_name,"htmlspecialchars")==0){
        return ori_htmlspecialchars_handler; 
    }else if(strcmp(func_name,"htmlentities")==0){
        return ori_htmlentities_handler; 
    }else if(strcmp(func_name,"addslashes")==0){
        return ori_addslashes_handler; 
    }else if(strcmp(func_name,"stripslashes")==0){
        return ori_stripslashes_handler; 
    }else if(strcmp(func_name,"strip_tags")==0){
        return ori_strip_tags_handler; 
    }else if(strcmp(func_name,"mysqli_real_escape_string")==0){
        return ori_mysqli_real_escape_string_handler; 
    }else if(strcmp(func_name,"preg_replace")==0){
        return ori_preg_replace_handler; 
    }else if(strcmp(func_name,"preg_match")==0){
        return ori_preg_match_handler; 
    }else if(strcmp(func_name,"realpath")==0){
        return ori_realpath_handler; 
    }else if(strcmp(func_name,"basename")==0){
        return ori_basename_handler; 
    }else if(strcmp(func_name,"escapeshellarg")==0){
        return ori_escapeshellarg_handler; 
    }else if(strcmp(func_name,"escapeshellcmd")==0){
        return ori_escapeshellcmd_handler; 
    }else if(strcmp(func_name,"str_replace")==0){
        return ori_str_replace_handler; 
    }else if(strcmp(func_name,"strpos")==0){
        return ori_strpos_handler; 
    }else if(strcmp(func_name,"stripos")==0){
        return ori_stripos_handler; 
    }else if(strcmp(func_name,"filter_var")==0){
        return ori_filter_var_handler; 
    }else if(strcmp(func_name,"filter_var_array")==0){
        return ori_filter_var_array_handler; 
    }else if(strcmp(func_name,"filter_input")==0){
        return ori_filter_input_handler;
    }else if(strcmp(func_name,"filter_input_array")==0){
        return ori_filter_input_array_handler; 
    }else if(strcmp(func_name,"libxml_disable_entity_loader")==0){
        return ori_libxml_disable_entity_loader_handler; 
    }else if(strcmp(func_name,"is_numeric")==0){
        return ori_is_numeric_handler;
    }else if(strcmp(func_name,"base64_decode")==0){
        return ori_base64_decode_handler; 
    }else if(strcmp(func_name,"json_decode")==0){
        return ori_json_decode_handler;
    }else if(strcmp(func_name,"fnmatch")==0){
        return ori_fnmatch_handler; 
    }else if(strcmp(func_name,"is_file")==0){
        return ori_is_file_handler; 
    }else if(strcmp(func_name,"file_exists")==0){
        return ori_file_exists_handler; 
    }else if(strcmp(func_name,"is_dir")==0){
        return ori_is_dir_handler; 
    }else if(strcmp(func_name,"is_executable")==0){
        return ori_is_executable_handler; 
    }else if(strcmp(func_name,"is_file")==0){
        return ori_is_file_handler; 
    }else if(strcmp(func_name,"is_link")==0){
        return ori_is_link_handler; 
    }else if(strcmp(func_name,"is_readable")==0){
        return ori_is_readable_handler; 
    }else if(strcmp(func_name,"is_writable")==0){
        return ori_is_writable_handler; 
    }else if(strcmp(func_name,"is_uploaded_file")==0){
        return ori_is_uploaded_file_handler; 
    }
}

void generic_sanitation_handler(zend_execute_data *execute_data, zval *return_value){
    char *func_name = ZSTR_VAL(execute_data->func->common.function_name);
    unsigned int num_args = ZEND_CALL_NUM_ARGS(execute_data); // Get number of arguments
    unsigned int i;
    char *func_name_str=NULL, *class_method = NULL;
    char *scope_name = NULL;

    if(execute_data->func && execute_data->func->common.scope){
        scope_name = ZSTR_VAL(execute_data->func->common.scope->name);
        // php_printf("%s::%s\n", scope_name, func_name);
        int length = strlen(scope_name) + strlen("::")+ strlen(func_name);
        class_method = emalloc(length+1);
        snprintf(class_method, length+1, "%s::%s", scope_name, func_name);
        func_name_str = class_method;
    }else{
        // php_printf("%s\n", func_name);
        func_name_str = func_name;
    }

    if(ZIMPAF_G(coverage_id) == NULL) {
        zif_handler sanitfunction_handler = get_sanitfunction_handler(scope_name, func_name);
        if(class_method != NULL){
            efree(class_method);
            class_method = NULL;
        }
        func_name_str = NULL;
        sanitfunction_handler(INTERNAL_FUNCTION_PARAM_PASSTHRU);
        return;
    }

    const char *filename = "Unknown";
    int lineno = 0;
    zend_execute_data *caller = execute_data->prev_execute_data; // Use execute_data
    if (caller && caller->func && caller->func->op_array.filename && caller->func->type == ZEND_USER_FUNCTION) {
        filename = ZSTR_VAL(caller->func->op_array.filename);
        lineno = caller->opline ? caller->opline->lineno : 0;
    }else{
        while (caller && caller->func && caller->func->type != ZEND_USER_FUNCTION){
            caller = caller->prev_execute_data;
        }
        if(caller && caller->func && caller->func->type == ZEND_USER_FUNCTION){
            filename = ZSTR_VAL(caller->func->op_array.filename);
            lineno = caller->opline ? caller->opline->lineno : 0;
        }
    }
    
    int arg_index = 0;
    int is_numeric_param_string = 0;
    zval *is_numeric_param = NULL; 
    cJSON *func_call = cJSON_CreateObject();
    cJSON_AddStringToObject(func_call, "function_name", func_name_str);
    if (strcmp(func_name_str, "str_replace") == 0 || 
        strcmp(func_name_str, "preg_replace") == 0) {
        arg_index = 3;
    } else if (strcmp(func_name_str, "mysqli_real_escape_string") == 0 ||
                strcmp(func_name_str, "preg_match") == 0 ||
                strcmp(func_name_str, "filter_input") == 0 ||
                strcmp(func_name_str, "fnmatch") == 0){
        arg_index = 2;
    } else if(strcmp(func_name_str, "is_numeric") == 0) {
        is_numeric_param = ZEND_CALL_ARG(execute_data, 1);
        arg_index = 1;
        if(Z_TYPE_P(is_numeric_param) == IS_STRING){
            is_numeric_param_string = 1;
        }   
    }else{
        arg_index = 1;
    }
    if(strcmp(func_name_str, "libxml_disable_entity_loader") == 0){
        zval *flag = ZEND_CALL_ARG(execute_data, 1);
        cJSON_AddBoolToObject(func_call, "disable", Z_TYPE_P(flag) == IS_TRUE);
    }else if(strcmp(func_name_str, "is_numeric") == 0 && is_numeric_param_string == 0) {
        char *str_param = NULL;
        if(Z_TYPE_P(is_numeric_param) == IS_ARRAY) {
            str_param = concatenate_zval_array_into_string(is_numeric_param);
        }else{
            str_param = get_func_param_string(is_numeric_param);
        }
        cJSON_AddStringToObject(func_call, "string", str_param);
        efree(str_param);
    }else if(strcmp(func_name_str, "filter_var_array") != 0 && //these functions do not have string arguments
        strcmp(func_name_str, "filter_input_array") != 0 &&
        strcmp(func_name_str, "filter_var") != 0 ){
        char *str_param = get_func_param_string(ZEND_CALL_ARG(execute_data, arg_index));
        cJSON_AddStringToObject(func_call, "string", str_param);
        efree(str_param);
    }else if(strcmp(func_name_str, "filter_var") == 0){
        zval *mixed_zv = ZEND_CALL_ARG(execute_data, 1);
        if(Z_TYPE_P(mixed_zv) == IS_STRING){
             cJSON_AddStringToObject(func_call, "mixed", Z_STRVAL_P(mixed_zv));
        }else if(Z_TYPE_P(mixed_zv) == IS_ARRAY){
            char *mixed_string = concatenate_zval_array_into_string(mixed_zv); //elements are concat to a string
            cJSON_AddStringToObject(func_call, "mixed", mixed_string); 
            efree(mixed_string);
        }
    }else if(strcmp(func_name_str, "filter_var_array") == 0){
        zval *array_zv = ZEND_CALL_ARG(execute_data, 1);
        char *array_string = concatenate_zval_array_into_string(array_zv);
        cJSON_AddStringToObject(func_call, "array", array_string); //elements are concat to a string
        if (array_string){
            efree(array_string);
        }
    }else if(strcmp(func_name_str, "filter_input_array") == 0){
        zval *array_zv = ZEND_CALL_ARG(execute_data, 1);
        char *array_string = get_input_superglobal(array_zv);
        cJSON_AddStringToObject(func_call, "array", array_string); //elements are concat to a string
        if (array_string){
            efree(array_string);
        }
    }
    if(arg_index == 3){
        zval *pattern_zv = ZEND_CALL_ARG(execute_data,1);
        char *pattern = NULL;
        if(Z_TYPE_P(pattern_zv) == IS_ARRAY){
            pattern = concatenate_zval_array_into_string(pattern_zv);
        }
        zval *replace_zv = ZEND_CALL_ARG(execute_data,2);
        char *replace = NULL;
        if(Z_TYPE_P(replace_zv) == IS_ARRAY){
            replace = concatenate_zval_array_into_string(replace_zv);
        }
        cJSON_AddStringToObject(func_call, "pattern", pattern ? pattern : Z_STRVAL_P(pattern_zv));
        cJSON_AddStringToObject(func_call, "replacement", replace ? replace : Z_STRVAL_P(replace_zv));
        if (pattern){
            efree(pattern);
        }
        if(replace){
            efree(replace);
        }
    }else if(strcmp(func_name_str, "preg_match") == 0 ||strcmp(func_name_str, "fnmatch") == 0){
        cJSON_AddStringToObject(func_call, "pattern", Z_STRVAL_P(ZEND_CALL_ARG(execute_data,1)));
    }else if(strcmp(func_name_str, "strpos") == 0 || strcmp(func_name_str, "stripos") == 0){
        cJSON_AddStringToObject(func_call, "pattern", Z_STRVAL_P(ZEND_CALL_ARG(execute_data,2)));
    }

        //check if ! operator is applied, e.g. !is_numeric("123")
    if(execute_data->prev_execute_data){
        const zend_op *opl_ret = (execute_data->prev_execute_data->opline + 1); 
        if(opl_ret->opcode == ZEND_BOOL_NOT ||opl_ret->opcode == ZEND_JMPNZ
           || opl_ret->opcode == ZEND_JMPNZ_EX){
            cJSON_AddNumberToObject(func_call, "negated", 1);
        }else{
            cJSON_AddNumberToObject(func_call, "negated", 0);
        }
    }

    cJSON_AddNumberToObject(func_call, "sanitation", 1); // Indicate this is a sanitation function
    cJSON_AddStringToObject(func_call, "filename", filename);
    cJSON_AddNumberToObject(func_call, "lineno", lineno);
    cJSON_AddItemToArray(ZIMPAF_G(func_call_seq), func_call);

    //call original handler 
    zif_handler sanitfunction_handler = get_sanitfunction_handler(scope_name, func_name);
    sanitfunction_handler(INTERNAL_FUNCTION_PARAM_PASSTHRU);

    if(Z_TYPE_P(return_value) == IS_STRING){
        // char *sanitized_string = Z_STRVAL_P(return_value);
        cJSON_AddStringToObject(func_call, "return_value", Z_STRVAL_P(return_value));  
    }else if(Z_TYPE_P(return_value) == IS_ARRAY){
        char *sanitized_string = concatenate_zval_array_into_string(return_value); //elements are concat to a string
        cJSON_AddStringToObject(func_call, "return_value", sanitized_string);
        efree(sanitized_string);
    }else if(Z_TYPE_P(return_value) == IS_LONG){
        cJSON_AddNumberToObject(func_call, "return_value", Z_LVAL_P(return_value));
    }else if(Z_TYPE_P(return_value) == IS_DOUBLE){
        cJSON_AddNumberToObject(func_call, "return_value", Z_DVAL_P(return_value));
    }else if(Z_TYPE_P(return_value) == IS_TRUE){
        cJSON_AddStringToObject(func_call, "return_value", "1");
    }else if(Z_TYPE_P(return_value) == IS_FALSE){
        cJSON_AddStringToObject(func_call, "return_value", "");
    }else{
        char *retval = get_return_value_string(return_value);
        cJSON_AddStringToObject(func_call, "return_value", retval);
        efree(retval);
    }

    if(class_method != NULL){
        efree(class_method);
        class_method = NULL;
    }
    func_name_str = NULL;
}

void hook_htmlspecialchars(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_htmlspecialchars_func = zend_hash_str_find_ptr(CG(function_table), "htmlspecialchars", sizeof("htmlspecialchars")-1);
    if (ori_htmlspecialchars_func && ori_htmlspecialchars_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_htmlspecialchars_handler = ori_htmlspecialchars_func->internal_function.handler;
        ori_htmlspecialchars_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }
}
void hook_htmlentities(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_htmlentities_func = zend_hash_str_find_ptr(CG(function_table), "htmlentities", sizeof("htmlentities")-1);
    if (ori_htmlentities_func && ori_htmlentities_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_htmlentities_handler = ori_htmlentities_func->internal_function.handler;
        ori_htmlentities_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }
}
void hook_addslashes(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_addslashes_func = zend_hash_str_find_ptr(CG(function_table), "addslashes", sizeof("addslashes")-1);
    if (ori_addslashes_func && ori_addslashes_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_addslashes_handler = ori_addslashes_func->internal_function.handler;
        ori_addslashes_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }
}
void hook_stripslashes(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_stripslashes_func = zend_hash_str_find_ptr(CG(function_table), "stripslashes", sizeof("stripslashes")-1);
    if (ori_stripslashes_func && ori_stripslashes_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_stripslashes_handler = ori_stripslashes_func->internal_function.handler;
        ori_stripslashes_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }
}
void hook_strip_tags(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_strip_tags_func = zend_hash_str_find_ptr(CG(function_table), "strip_tags", sizeof("strip_tags")-1);
    if (ori_strip_tags_func && ori_strip_tags_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_strip_tags_handler = ori_strip_tags_func->internal_function.handler;
        ori_strip_tags_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }
}
void hook_mysqli_real_escape_string(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_mysqli_real_escape_string_func = zend_hash_str_find_ptr(CG(function_table), "mysqli_real_escape_string", sizeof("mysqli_real_escape_string")-1);
    if (ori_mysqli_real_escape_string_func && ori_mysqli_real_escape_string_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_mysqli_real_escape_string_handler = ori_mysqli_real_escape_string_func->internal_function.handler;
        ori_mysqli_real_escape_string_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }
}

void hook_mysqli_real_escape_string_cm(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_class_entry *mysqli_ce = zend_hash_str_find_ptr(CG(class_table), "mysqli", sizeof("mysqli")-1);
    if (mysqli_ce) {
        zend_function *mysqli_real_escape_string_method = zend_hash_str_find_ptr(&mysqli_ce->function_table, "real_escape_string", sizeof("real_escape_string") - 1);
        if (mysqli_real_escape_string_method && mysqli_real_escape_string_method->type == ZEND_INTERNAL_FUNCTION) {
            ori_mysqli_real_escape_string_method_handler = mysqli_real_escape_string_method->internal_function.handler;
            mysqli_real_escape_string_method->internal_function.handler = (zif_handler)generic_sanitation_handler;
        }
    }
}

void hook_pdo_quote_cm(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
		ZEND_TSRMLS_CACHE_UPDATE();
	#endif
    zend_class_entry *pdo_ce = zend_hash_str_find_ptr(CG(class_table), "pdo", sizeof("pdo")-1);
    if (pdo_ce) {
        zend_function *pdo_quote_method = zend_hash_str_find_ptr(&pdo_ce->function_table, "quote", sizeof("quote") - 1);
        if (pdo_quote_method && pdo_quote_method->type == ZEND_INTERNAL_FUNCTION) {
            ori_pdo_quote_method_handler = pdo_quote_method->internal_function.handler;
            pdo_quote_method->internal_function.handler = generic_sanitation_handler;
        }
    }
}

void hook_escapeshellarg(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_escapeshellarg_func = zend_hash_str_find_ptr(CG(function_table), "escapeshellarg", sizeof("escapeshellarg")-1);
    if (ori_escapeshellarg_func && ori_escapeshellarg_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_escapeshellarg_handler = ori_escapeshellarg_func->internal_function.handler;
        ori_escapeshellarg_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }
}

void hook_escapeshellcmd(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_escapeshellcmd_func = zend_hash_str_find_ptr(CG(function_table), "escapeshellcmd", sizeof("escapeshellcmd")-1);
    if (ori_escapeshellcmd_func && ori_escapeshellcmd_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_escapeshellcmd_handler = ori_escapeshellcmd_func->internal_function.handler;
        ori_escapeshellcmd_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }
}

void hook_preg_replace(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST) 
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_preg_replace_func = zend_hash_str_find_ptr(CG(function_table), "preg_replace", sizeof("preg_replace")-1);
    if (ori_preg_replace_func && ori_preg_replace_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_preg_replace_handler = ori_preg_replace_func->internal_function.handler;
        ori_preg_replace_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }
}

void hook_preg_match(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST) 
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_preg_match_func = zend_hash_str_find_ptr(CG(function_table), "preg_match", sizeof("preg_match")-1);
    if (ori_preg_match_func && ori_preg_match_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_preg_match_handler = ori_preg_match_func->internal_function.handler;
        ori_preg_match_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }

}

void hook_realpath(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST) 
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_realpath_func = zend_hash_str_find_ptr(CG(function_table), "realpath", sizeof("realpath")-1);
    if (ori_realpath_func && ori_realpath_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_realpath_handler = ori_realpath_func->internal_function.handler;
        ori_realpath_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }
}

void hook_basename(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_basename_func = zend_hash_str_find_ptr(CG(function_table), "basename", sizeof("basename")-1);
    if (ori_basename_func && ori_basename_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_basename_handler = ori_basename_func->internal_function.handler;
        ori_basename_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }
}

void hook_str_replace(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_str_replace_func = zend_hash_str_find_ptr(CG(function_table), "str_replace", sizeof("str_replace")-1);
    if (ori_str_replace_func && ori_str_replace_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_str_replace_handler = ori_str_replace_func->internal_function.handler;
        ori_str_replace_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }
}
void hook_strpos(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_strpos_func = zend_hash_str_find_ptr(CG(function_table), "strpos", sizeof("strpos")-1);
    if (ori_strpos_func && ori_strpos_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_strpos_handler = ori_strpos_func->internal_function.handler;
        ori_strpos_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }
}
void hook_stripos(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_stripos_func = zend_hash_str_find_ptr(CG(function_table), "stripos", sizeof("stripos")-1);
    if (ori_stripos_func && ori_stripos_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_stripos_handler = ori_stripos_func->internal_function.handler;
        ori_stripos_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }
}

void hook_filter_var(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_filter_var_func = zend_hash_str_find_ptr(CG(function_table), "filter_var", sizeof("filter_var")-1);
    if (ori_filter_var_func && ori_filter_var_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_filter_var_handler = ori_filter_var_func->internal_function.handler;
        ori_filter_var_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }
}

void hook_filter_var_array(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_filter_var_array_func = zend_hash_str_find_ptr(CG(function_table), "filter_var_array", sizeof("filter_var_array")-1);
    if (ori_filter_var_array_func && ori_filter_var_array_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_filter_var_array_handler = ori_filter_var_array_func->internal_function.handler;
        ori_filter_var_array_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }
}
void hook_filter_input(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_filter_input_func = zend_hash_str_find_ptr(CG(function_table), "filter_input", sizeof("filter_input")-1);
    if (ori_filter_input_func && ori_filter_input_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_filter_input_handler = ori_filter_input_func->internal_function.handler;
        ori_filter_input_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }
}
void hook_filter_input_array(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_filter_input_array_func = zend_hash_str_find_ptr(CG(function_table), "filter_input_array", sizeof("filter_input_array")-1);
    if (ori_filter_input_array_func && ori_filter_input_array_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_filter_input_array_handler = ori_filter_input_array_func->internal_function.handler;
        ori_filter_input_array_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }
}
void hook_libxml_disable_entity_loader(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_libxml_disable_entity_loader_func = zend_hash_str_find_ptr(CG(function_table), "libxml_disable_entity_loader", 
                                                                                            sizeof("libxml_disable_entity_loader")-1);
    if (ori_libxml_disable_entity_loader_func && ori_libxml_disable_entity_loader_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_libxml_disable_entity_loader_handler = ori_libxml_disable_entity_loader_func->internal_function.handler;
        ori_libxml_disable_entity_loader_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }
}

void hook_is_numeric(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_is_numeric_func = zend_hash_str_find_ptr(CG(function_table), "is_numeric", sizeof("is_numeric")-1);
    if (ori_is_numeric_func && ori_is_numeric_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_is_numeric_handler = ori_is_numeric_func->internal_function.handler;
        ori_is_numeric_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    } 
}

void hook_base64_decode(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_base64_decode_func = zend_hash_str_find_ptr(CG(function_table), "base64_decode", sizeof("base64_decode")-1);
    if (ori_base64_decode_func && ori_base64_decode_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_base64_decode_handler = ori_base64_decode_func->internal_function.handler;
        ori_base64_decode_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    } 
}

void hook_json_decode(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_json_decode_func = zend_hash_str_find_ptr(CG(function_table), "json_decode", sizeof("json_decode")-1);
    if (ori_json_decode_func && ori_json_decode_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_json_decode_handler = ori_json_decode_func->internal_function.handler;
        ori_json_decode_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    } 
}

void hook_fnmatch(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_fnmatch_func = zend_hash_str_find_ptr(CG(function_table), "fnmatch", sizeof("fnmatch")-1);
    if (ori_fnmatch_func && ori_fnmatch_func->type == ZEND_INTERNAL_FUNCTION) {
        // Save the original handler if needed
        ori_fnmatch_handler = ori_fnmatch_func->internal_function.handler;
        ori_fnmatch_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }
}

void hook_is_file(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_is_file_func = zend_hash_str_find_ptr(CG(function_table), "is_file", sizeof("is_file")-1);
    if (ori_is_file_func && ori_is_file_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_is_file_handler = ori_is_file_func->internal_function.handler;
        ori_is_file_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    } 
}

void hook_file_exists(){            //payload in 1st arg
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_file_exists_func = zend_hash_str_find_ptr(CG(function_table), "file_exists", sizeof("file_exists")-1);
    if (ori_file_exists_func && ori_file_exists_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_file_exists_handler = ori_file_exists_func->internal_function.handler;
        ori_file_exists_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }
} 

void hook_is_dir(){             //payload in 1st arg
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_is_dir_func = zend_hash_str_find_ptr(CG(function_table), "is_dir", sizeof("is_dir")-1);
    if (ori_is_dir_func && ori_is_dir_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_is_dir_handler = ori_is_dir_func->internal_function.handler;
        ori_is_dir_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }
}         

void hook_is_executable(){      //payload in 1st arg
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_is_executable_func = zend_hash_str_find_ptr(CG(function_table), "is_executable", sizeof("is_executable")-1);
    if (ori_is_executable_func && ori_is_executable_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_is_executable_handler = ori_is_executable_func->internal_function.handler;
        ori_is_executable_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }
}    
 
void hook_is_link(){        //payload in 1st arg
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_is_link_func = zend_hash_str_find_ptr(CG(function_table), "is_link", sizeof("is_link")-1);
    if (ori_is_link_func && ori_is_link_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_is_link_handler = ori_is_link_func->internal_function.handler;
        ori_is_link_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }
}        

void hook_is_readable(){        //payload in 1st arg
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_is_readable_func = zend_hash_str_find_ptr(CG(function_table), "is_readable", sizeof("is_readable")-1);
    if (ori_is_readable_func && ori_is_readable_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_is_readable_handler = ori_is_readable_func->internal_function.handler;
        ori_is_readable_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }
}    

void hook_is_writable(){            //payload in 1st arg
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_is_writable_func = zend_hash_str_find_ptr(CG(function_table), "is_writable", sizeof("is_writable")-1);
    if (ori_is_writable_func && ori_is_writable_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_is_writable_handler = ori_is_writable_func->internal_function.handler;
        ori_is_writable_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }
}    

void hook_is_uploaded_file(){       //payload in 1st arg    
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_is_uploaded_file_func = zend_hash_str_find_ptr(CG(function_table), "is_uploaded_file", sizeof("is_uploaded_file")-1);
    if (ori_is_uploaded_file_func && ori_is_uploaded_file_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_is_uploaded_file_handler = ori_is_uploaded_file_func->internal_function.handler;
        ori_is_uploaded_file_func->internal_function.handler = (zif_handler)generic_sanitation_handler;
    }
} 
