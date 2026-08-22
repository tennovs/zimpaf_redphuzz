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

#include "../include/flowfunchook.h"
#include "../php_zimpaf.h"
#include "../include/utils.h"

zif_handler ori_exit_handler = NULL;                                

zif_handler get_flowfunction_handler(char *scope_name, char *func_name);

zif_handler get_flowfunction_handler(char *scope_name, char *func_name){
    if(scope_name && func_name){
        //do nothing for now since there is no function in a class that is hooked
        return NULL;
    }else if(strcmp(func_name,"exit")==0){
        return ori_exit_handler; 
    }
}

void generic_flowfunction_handler(zend_execute_data *execute_data, zval *return_value){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif

    char *func_name = ZSTR_VAL(execute_data->func->common.function_name);
    unsigned int num_args = ZEND_CALL_NUM_ARGS(execute_data); // Get number of arguments
    char *func_name_str = NULL, *class_method = NULL;

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
        zif_handler flowfunction_handler = get_flowfunction_handler(scope_name, func_name);
        if(class_method != NULL){
            efree(class_method);
            class_method = NULL;
        }
        func_name_str = NULL;
        flowfunction_handler(INTERNAL_FUNCTION_PARAM_PASSTHRU);
        return;
    }

    const char *filename = "Unknown";
    int lineno = 0;
    
    zend_execute_data *caller = execute_data->prev_execute_data; // Use execute_data
    if (caller && caller->func && caller->func->op_array.filename) {
        filename = ZSTR_VAL(caller->func->op_array.filename);
        lineno = caller->opline ? caller->opline->lineno : 0;
    }

     
    cJSON *func_call = cJSON_CreateObject();
	cJSON_AddStringToObject(func_call, "function_name", func_name_str);
    cJSON_AddStringToObject(func_call, "filename", filename);
    cJSON_AddNumberToObject(func_call, "lineno", lineno);
    cJSON_AddItemToArray(ZIMPAF_G(func_call_seq), func_call);

    //call original handler
    zif_handler flowfunction_handler = get_flowfunction_handler(scope_name, func_name);
    flowfunction_handler(INTERNAL_FUNCTION_PARAM_PASSTHRU);

    if(class_method != NULL){
        efree(class_method);
        class_method = NULL;
    }
    func_name_str = NULL;
    
    char *retval_str = get_return_value_string(return_value);
    cJSON_AddStringToObject(func_call, "return_value", retval_str);
    efree(retval_str);
}

void hook_exit(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
  
    zend_function *ori_exit_func = zend_hash_str_find_ptr(CG(function_table), "exit", sizeof("exit")-1);
    if (ori_exit_func && ori_exit_func->type == ZEND_INTERNAL_FUNCTION) {
        //printf("Hooking exit function\n");
        //printf("Original exit function: %s\n", ZSTR_VAL(ori_exit_func->common.function_name));
        //printf("Original exit handler: %p\n", ori_exit_func->internal_function.handler);
        ori_exit_handler = ori_exit_func->internal_function.handler;
        ori_exit_func->internal_function.handler = (zif_handler) generic_flowfunction_handler;
        //printf("New exit handler: %p\n", ori_exit_func->internal_function.handler);
    }
}
