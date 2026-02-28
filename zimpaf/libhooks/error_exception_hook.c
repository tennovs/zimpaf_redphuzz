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


#include "../include/error_exception_hook.h"
#include "../php_zimpaf.h"
#include "zend_exceptions.h"
#include "zend.h"
#include "../include/utils.h"

static void (*original_zend_error_cb)(int type, zend_string *error_filename, 
                                    const uint error_lineno,zend_string *message);
static void (*original_zend_throw_exception_hook)(zend_object *ex);

static void log_error(int type, zend_string *error_filename, const uint error_lineno, 
                                                                zend_string *message);
static void log_exception(zend_object *ex);
unsigned int error_or_exception_last_caught(char *filename, unsigned int lineno, char *funcname);


static void zend_error_cb_handler(int type, zend_string *error_filename, const uint error_lineno, 
                                                                            zend_string *message){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif

    if (ZIMPAF_G(coverage_id) == NULL) {
        if (original_zend_error_cb) {
            original_zend_error_cb(type, error_filename, error_lineno, message);
            return;
        }
    }

// normal instrumentation logic...

    
    // zend_error_info **errors = EG(errors);
    zend_object *ex = NULL;
    if (EG(exception)) {
        ex = EG(exception);
    }else if(EG(prev_exception)){
        ex = EG(prev_exception);
    }
    
    if (ex) {
        zimpaf_observe_exception(EG(exception));
    }else{
        log_error(type, error_filename, error_lineno, message);    
    }

    if (original_zend_error_cb) {
        original_zend_error_cb(type, error_filename, error_lineno, message);
    }
}

static void log_error(int type, zend_string *error_filename, const uint error_lineno, 
                                                                zend_string *message){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    
    char *func_name = NULL;
    char *func_name_str=NULL, *class_method = NULL;
    char *scope_name = NULL;

    zend_execute_data *execute_data = EG(current_execute_data);
    if (execute_data && execute_data->func && execute_data->func->common.function_name){ 
        func_name = ZSTR_VAL(execute_data->func->common.function_name);
    }
 
    if(execute_data && execute_data->func && execute_data->func->common.scope && 
                                        execute_data->func->common.function_name){
        scope_name = ZSTR_VAL(execute_data->func->common.scope->name);
        int length = strlen(scope_name) + strlen("::")+ strlen(func_name);
        class_method = emalloc(length+1);
        snprintf(class_method, length+1, "%s::%s", scope_name, func_name);
        func_name_str = class_method;
    }else if (func_name != NULL){
        func_name_str = func_name;
    }

    //This is to extract the function name from the error message.
    unsigned int fname_extraction_flag = 0;
    char *extracted_func_name = NULL;
    if (func_name_str == NULL){
        fname_extraction_flag = 1;
        char *error_msg = ZSTR_VAL(message);
        extracted_func_name = extract_function_name(error_msg);
    }

    //Avoid duplicate exception that has been logged and now, it is caught as an error.
    if(fname_extraction_flag == 0){
        if(error_or_exception_last_caught(ZSTR_VAL(error_filename), error_lineno, func_name_str)){
            if(class_method != NULL){
                efree(class_method);
                class_method = NULL;
            }
            func_name_str = NULL;
            return;
        }
    }else{
        if(error_or_exception_last_caught(ZSTR_VAL(error_filename), error_lineno, extracted_func_name)){
            if(extracted_func_name != NULL){
                efree(extracted_func_name);
                extracted_func_name = NULL;
            }
            return;
        }
    }

    if(func_name_str != NULL && strcmp(func_name_str, "session_start") == 0){
        return;
    }else if(extracted_func_name != NULL && strcmp(extracted_func_name, "session_start") == 0){
        return;
    }

    cJSON *generic_error_log = cJSON_CreateObject();
    cJSON_AddNumberToObject(generic_error_log, "type", type);
    if(fname_extraction_flag == 0){
        cJSON_AddStringToObject(generic_error_log, "function_name", func_name_str ? func_name_str : "");
    }else{
        cJSON_AddStringToObject(generic_error_log, "function_name", extracted_func_name ? extracted_func_name : "");  
    }
    cJSON_AddStringToObject(generic_error_log,"error", ZSTR_VAL(message));
    cJSON_AddStringToObject(generic_error_log, "filename", ZSTR_VAL(error_filename));
    cJSON_AddNumberToObject(generic_error_log, "lineno", error_lineno);
    
    // cJSON_AddItemToArray(ZIMPAF_G(error_log), generic_error_log);
    char *json_string = cJSON_PrintUnformatted(generic_error_log);

    if(json_string!= NULL){
        printf("%s\n", json_string);    
        if(ZIMPAF_G(coverage_id) != NULL){
            char generic_error_dir[] = "/shared-tmpfs/error-reports";
            unsigned int len_generic_error_fname = strlen(generic_error_dir) + strlen("/")+strlen(ZIMPAF_G(coverage_id))+strlen(".json")+1;
            char generic_error_fname[len_generic_error_fname];
            snprintf(generic_error_fname, len_generic_error_fname, "%s/%s.json", generic_error_dir, ZIMPAF_G(coverage_id));
            FILE *f = fopen(generic_error_fname, "a");
            if (f) {
                fputs(json_string, f);
                fputs("\n",f);
                fclose(f);
            }
        }
        free(json_string); // Free the JSON string
    }
    cJSON_Delete(generic_error_log);
    generic_error_log = NULL;

    //Avoid duplicate exception that has been logged and this, it is caught as an error.
    ZIMPAF_G(error_exception_just_logged) = 1;
    if (ZIMPAF_G(error_exception_just_logged_filename)) {
        efree(ZIMPAF_G(error_exception_just_logged_filename));
        ZIMPAF_G(error_exception_just_logged_filename) = NULL; 
    }
    
    if (error_filename) {
        ZIMPAF_G(error_exception_just_logged_filename) = estrndup(ZSTR_VAL(error_filename), 
                                                              ZSTR_LEN(error_filename));
    }

    ZIMPAF_G(error_exception_just_logged_lineno) = error_lineno;

    if (ZIMPAF_G(error_exception_just_logged_funcname)) {
        efree(ZIMPAF_G(error_exception_just_logged_funcname));
        ZIMPAF_G(error_exception_just_logged_funcname) = NULL; 
    }
    if (fname_extraction_flag && extracted_func_name != NULL) {
        ZIMPAF_G(error_exception_just_logged_funcname) = estrdup(extracted_func_name);
    }else if(!fname_extraction_flag && func_name_str != NULL){
        ZIMPAF_G(error_exception_just_logged_funcname) = estrdup(func_name_str);
    }

    func_name_str = NULL;
    if(class_method != NULL){
        efree(class_method);
        class_method = NULL;
    }
    if(extracted_func_name != NULL){
        efree(extracted_func_name);
        extracted_func_name = NULL;
    }
}

static void zend_throw_exception_hook_handler(zend_object *ex){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif

    if (ZIMPAF_G(coverage_id) == NULL) {
        if (original_zend_throw_exception_hook) {
            original_zend_throw_exception_hook(ex);
            return;
        }
    }

    if (ZIMPAF_G(last_observed_ex) == ex) {
        return;
    }
    ZIMPAF_G(last_observed_ex) = ex;
    log_exception(ex);

    if (original_zend_throw_exception_hook) {
        original_zend_throw_exception_hook(ex);
    }
}

static void log_exception(zend_object *ex){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif

    char *func_name = NULL;
    char *func_name_str=NULL, *class_method = NULL;
    char *scope_name = NULL;

    zend_execute_data *execute_data = EG(current_execute_data);
    if (execute_data && execute_data->func && execute_data->func->common.function_name){ 
        func_name = ZSTR_VAL(execute_data->func->common.function_name);
    }
 
    if(execute_data && execute_data->func && execute_data->func->common.scope && 
                                        execute_data->func->common.function_name){
        scope_name = ZSTR_VAL(execute_data->func->common.scope->name);
        int length = strlen(scope_name) + strlen("::")+ strlen(func_name);
        class_method = emalloc(length+1);
        snprintf(class_method, length+1, "%s::%s", scope_name, func_name);
        func_name_str = class_method;
    }else if(func_name != NULL){
        func_name_str = func_name;
    }

    // Read properties directly from the object (Safe, no PHP method calls)
    zval *message = zend_read_property(ex->ce, ex, "message", sizeof("message")-1, 1, NULL);
    zval *code = zend_read_property(ex->ce, ex, "code", sizeof("code")-1, 1, NULL);
    zval *file = zend_read_property(ex->ce, ex, "file", sizeof("file")-1, 1, NULL);
    zval *line = zend_read_property(ex->ce, ex, "line", sizeof("line")-1, 1, NULL);

    //Avoid duplicate exception that has been logged and now, it is caught as an exception.
    if(error_or_exception_last_caught(Z_STRVAL_P(file), (int)Z_LVAL_P(line), func_name_str)){
        func_name_str = NULL;
        if(class_method != NULL){
            efree(class_method);
            class_method = NULL;
        }
        return;
    }

    cJSON *exception_log = cJSON_CreateObject();
    cJSON_AddStringToObject(exception_log, "function_name", func_name_str ? func_name_str : "");
    cJSON_AddNumberToObject(exception_log, "code", Z_LVAL_P(code));
    cJSON_AddStringToObject(exception_log,"message", Z_STRVAL_P(message));
    cJSON_AddStringToObject(exception_log, "filename", Z_STRVAL_P(file));
    cJSON_AddNumberToObject(exception_log, "lineno", Z_LVAL_P(line));
    // cJSON_AddItemToArray(ZIMPAF_G(exception_log), exception_log);
    char *json_string = cJSON_PrintUnformatted(exception_log);

    if(json_string!= NULL){
        printf("%s\n", json_string);    
        if(ZIMPAF_G(coverage_id) != NULL){
            char exception_dir[] = "/shared-tmpfs/exception-reports";
            unsigned int len_exception_fname = strlen(exception_dir) + strlen("/")+strlen(ZIMPAF_G(coverage_id))+strlen(".json")+1;
            char exception_fname[len_exception_fname];
            snprintf(exception_fname, len_exception_fname, "%s/%s.json", exception_dir, ZIMPAF_G(coverage_id));
            FILE *f = fopen(exception_fname, "a");
            if (f) {
                fputs(json_string, f);
                fputs("\n",f);
                fclose(f);
            }
        }
        free(json_string); // Free the JSON string
    }
    cJSON_Delete(exception_log);
    exception_log = NULL;

    //Avoid duplicate exception that has been logged and this, it is caught as an error.    
    ZIMPAF_G(error_exception_just_logged) = 1;
    if (ZIMPAF_G(error_exception_just_logged_filename)) {
        efree(ZIMPAF_G(error_exception_just_logged_filename));
        ZIMPAF_G(error_exception_just_logged_filename) = NULL; // CRITICAL: Prevent Use-After-Free
    }
    
    if (file && Z_TYPE_P(file) == IS_STRING) {
        ZIMPAF_G(error_exception_just_logged_filename) = estrndup(Z_STRVAL_P(file), Z_STRLEN_P(file));
    }

    ZIMPAF_G(error_exception_just_logged_lineno) = (line && Z_TYPE_P(line) == IS_LONG) ? (int)Z_LVAL_P(line) : 0;

    if (ZIMPAF_G(error_exception_just_logged_funcname)) {
        efree(ZIMPAF_G(error_exception_just_logged_funcname));
        ZIMPAF_G(error_exception_just_logged_funcname) = NULL; // CRITICAL: Prevent Double-Free
    }
    if (func_name_str != NULL) {
        ZIMPAF_G(error_exception_just_logged_funcname) = estrdup(func_name_str);
    }

    func_name_str = NULL;
    if(class_method != NULL){
        efree(class_method);
        class_method = NULL;
    }
}

void hook_zend_error_cb(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    original_zend_error_cb = zend_error_cb;
    zend_error_cb = zend_error_cb_handler;
}

void hook_zend_throw_exception_hook(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    original_zend_throw_exception_hook = zend_throw_exception_hook;
    zend_throw_exception_hook = zend_throw_exception_hook_handler;
}

//error observer code
void zimpaf_observer_error_handler(int type, zend_string *error_filename, uint32_t error_lineno, 
                                     zend_string *message){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
 
    if (ZIMPAF_G(coverage_id) == NULL) {
        return; // just skip your instrumentation
    }

    zend_object *ex = NULL;

    if (EG(exception)) {
        ex = EG(exception);
    }else if(EG(prev_exception)){
        ex = EG(prev_exception);
    }

    if(ex){
        zimpaf_observe_exception(EG(exception));
    }else{   
        switch (type) {
            case E_ERROR:
            case E_PARSE:
            case E_CORE_ERROR:
            case E_CORE_WARNING:
            case E_COMPILE_ERROR:
            case E_COMPILE_WARNING:
                return; // Exit early, let the zend_error_cb handle this to avoid duplicate report
        }
        log_error(type, error_filename, error_lineno, message);
    }
}

void zimpaf_observe_exception(zend_object *ex) {
      #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    //no need for early termination guard, it is already handled in zimpaf.c, 
    //exception_via_zend_throw_handler(zend_execute_data *execute_data)
    if (ZIMPAF_G(last_observed_ex) == ex) {
        return;
    }
    ZIMPAF_G(last_observed_ex) = ex;
    log_exception(ex);
}

unsigned int error_or_exception_last_caught(char *filename, unsigned int lineno, char *funcname){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
		ZEND_TSRMLS_CACHE_UPDATE();
	#endif

    char *ejl_filename = ZIMPAF_G(error_exception_just_logged_filename);
    char *ejl_funcname = ZIMPAF_G(error_exception_just_logged_funcname);
    unsigned int filename_flag = 0;
    unsigned int funcname_flag = 0;

    if(ejl_filename != NULL && filename != NULL &&
        strcmp(ejl_filename,filename) == 0){
        filename_flag = 1;
    }
    if(ejl_funcname != NULL && funcname != NULL &&
        strcmp(ejl_funcname, funcname)  == 0){
        funcname_flag = 1;
    }
    if( ZIMPAF_G(error_exception_just_logged) == 1 && filename_flag && 
        (int) ZIMPAF_G(error_exception_just_logged_lineno) == lineno &&
        funcname_flag){
        return 1;
    }else{
        return 0;
    }
}

