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


#include "../include/deserhook.h"
#include "../include/utils.h"
#include "../php_zimpaf.h"

zif_handler ori_unserialize_handler = NULL;                     //unserialize
zif_handler ori_yaml_parse_handler = NULL;                      //yaml_parse
zif_handler ori_yaml_parse_file_handler = NULL;                 //yaml_parse_file
zif_handler ori_unpack_handler = NULL;                          //msgpack_unpack
zif_handler ori_igbinary_unserialize_handler = NULL;            //igbinary_unserialize
zif_handler ori_phar_decompress_method_handler = NULL;          //phar::decompress
zif_handler get_deserfunction_handler(char *scope_name, char *func_name);

zif_handler get_deserfunction_handler(char *scope_name, char *func_name){
    if(scope_name && func_name){
        if(strcmp(scope_name,"Phar")== 0){
            if(strcmp(func_name,"decompress")==0){
                //commented right now, further investigation is needed to see if this can be called directly or not, and if it can be called directly, we need to check the parameters and return value --- IGNORE ---
                // return ori_phar_decompress_method_handler;
            }
        }
    }else if(strcmp(func_name,"unserialize")==0){
        return ori_unserialize_handler; 
    }else if(strcmp(func_name,"yaml_parse")==0){
        return ori_yaml_parse_handler;
    }else if(strcmp(func_name,"yaml_parse_file")==0){
        return ori_yaml_parse_file_handler;
    }else if(strcmp(func_name,"unpack")==0){
        return ori_unpack_handler; 
    }else if(strcmp(func_name,"igbinary_unserialize")==0){
        return ori_igbinary_unserialize_handler; 
    }
}

void generic_deserialization_handler(zend_execute_data *execute_data, zval *return_value){
    char *func_name = ZSTR_VAL(execute_data->func->common.function_name);
    unsigned int num_args = ZEND_CALL_NUM_ARGS(execute_data); // Get number of arguments
    unsigned int i;
    char *func_name_str=NULL, *class_method = NULL;

    char *scope_name = NULL;
    if(execute_data->func && execute_data->func->common.scope && func_name){
        scope_name = ZSTR_VAL(execute_data->func->common.scope->name);
        int length = strlen(scope_name) + strlen("::")+ strlen(func_name);
        class_method = emalloc(length+1);
        snprintf(class_method, length+1, "%s::%s", scope_name, func_name);
        func_name_str = class_method;
    }else{
        func_name_str = func_name;
    }

    if (ZIMPAF_G(coverage_id) == NULL) {
        zif_handler deserfunction_handler = get_deserfunction_handler(scope_name, func_name);
        if(class_method != NULL){
            efree(class_method);
            class_method = NULL;
        }
        func_name_str = NULL;
        deserfunction_handler(INTERNAL_FUNCTION_PARAM_PASSTHRU);
        return;
    }

    const char *filename = "Unknown";
    int lineno = 0;
    zend_execute_data *caller = execute_data->prev_execute_data; // Use execute_data
    if (caller && caller->func && caller->func->op_array.filename) {
        filename = ZSTR_VAL(caller->func->op_array.filename);
        lineno = caller->opline ? caller->opline->lineno : 0;
    }
    zval *string = NULL;
    if(strcmp(func_name_str, "unpack") == 0){
        string = ZEND_CALL_ARG(execute_data, 2);
    }else{ 
        string = ZEND_CALL_ARG(execute_data, 1);
    }
    unsigned int sink_opline_type = is_func_param_string_literal(string, execute_data);    

    cJSON *func_call = cJSON_CreateObject();
    cJSON_AddStringToObject(func_call, "function_name", func_name_str);
    cJSON_AddStringToObject(func_call, "serialized_string", Z_STRVAL_P(string));
     cJSON_AddNumberToObject(func_call, "sink_opline_type", sink_opline_type);
    cJSON_AddStringToObject(func_call, "filename", filename);
    cJSON_AddNumberToObject(func_call, "lineno", lineno);
    cJSON_AddItemToArray(ZIMPAF_G(func_call_seq), func_call);

    //call original handler 
    zif_handler deserfunction_handler = get_deserfunction_handler(scope_name, func_name);
    deserfunction_handler(INTERNAL_FUNCTION_PARAM_PASSTHRU);

    if(class_method != NULL){
        efree(class_method);
        class_method = NULL;
    }
    func_name_str = NULL;

    char *retval_str = get_return_value_string(return_value);
    cJSON_AddStringToObject(func_call, "return_value", retval_str);
    efree(retval_str);
}

void hook_unserialize(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_unserialize_func = zend_hash_str_find_ptr(CG(function_table), "unserialize", sizeof("unserialize")-1);
    if (ori_unserialize_func && ori_unserialize_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_unserialize_handler = ori_unserialize_func->internal_function.handler;
        ori_unserialize_func->internal_function.handler = (zif_handler)generic_deserialization_handler;
    }
}

void hook_yaml_parse(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_yaml_parse_func = zend_hash_str_find_ptr(CG(function_table), "yaml_parse", sizeof("yaml_parse")-1);
    if (ori_yaml_parse_func && ori_yaml_parse_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_yaml_parse_handler = ori_yaml_parse_func->internal_function.handler;
        ori_yaml_parse_func->internal_function.handler = (zif_handler)generic_deserialization_handler;
    }
}

void hook_yaml_parse_file(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_yaml_parse_file_func = zend_hash_str_find_ptr(CG(function_table), "yaml_parse_file", sizeof("yaml_parse_file")-1);
    if (ori_yaml_parse_file_func && ori_yaml_parse_file_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_yaml_parse_file_handler = ori_yaml_parse_file_func->internal_function.handler;
        ori_yaml_parse_file_func->internal_function.handler = (zif_handler)generic_deserialization_handler;
    }
}
void hook_unpack(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_unpack_func = zend_hash_str_find_ptr(CG(function_table), "unpack", sizeof("unpack")-1);
    if (ori_unpack_func && ori_unpack_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_unpack_handler = ori_unpack_func->internal_function.handler;
        ori_unpack_func->internal_function.handler = (zif_handler)generic_deserialization_handler;
    }
}
void hook_igbinary_unserialize(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_igbinary_unserialize_func = zend_hash_str_find_ptr(CG(function_table), "igbinary_unserialize", sizeof("igbinary_unserialize")-1);
    if (ori_igbinary_unserialize_func && ori_igbinary_unserialize_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_igbinary_unserialize_handler = ori_igbinary_unserialize_func->internal_function.handler;
        ori_igbinary_unserialize_func->internal_function.handler = (zif_handler)generic_deserialization_handler;
    }
}
//commented right now, further investigation is needed to see if this can be called directly or not, and if it can be called directly, we need to check the parameters and return value --- IGNORE ---  
// void hook_phar_decompress_cm(){
//     #if defined(ZTS) && defined(COMPILE_DL_TEST)
//         ZEND_TSRMLS_CACHE_UPDATE();
//     #endif
//     zend_class_entry *phar_ce = zend_hash_str_find_ptr(CG(class_table), "phar", sizeof("phar")-1);
//     if (phar_ce) {
//         zend_function *phar_decompress_method = zend_hash_str_find_ptr(&phar_ce->function_table, "decompress", sizeof("decompress") - 1);
//         if (phar_decompress_method && phar_decompress_method->type == ZEND_INTERNAL_FUNCTION) {
//             ori_phar_decompress_method_handler = phar_decompress_method->internal_function.handler;
//             phar_decompress_method->internal_function.handler = (zif_handler)generic_deserialization_handler;
//         }
//     }
// }

