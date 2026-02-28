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


#include "../include/dbhook.h"
#include "../include/utils.h"
#include "../php_zimpaf.h"
#include "ext/standard/php_standard.h"
#include "Zend/zend_smart_str.h"
#include "zend_types.h"
#include "zend_compile.h"
#include "zend_globals_macros.h"
#include "zend_string.h"
#include "zend.h"
#include "ext/mysqli/php_mysqli_structs.h"
// #include ext/mysqli/mysqli_mysqlnd.h
#include "ext/pdo/php_pdo_driver.h"
#include "ext/pdo/php_pdo.h"
   
zif_handler ori_mysqli_query_handler = NULL;                    //mysqli_query               
zif_handler ori_mysqli_query_method_handler = NULL;             //mysqli::query     
zif_handler ori_pdo_query_method_handler = NULL;                //pdo::query
zif_handler ori_mysqli_real_query_handler;                      //mysqli_real_query
zif_handler ori_mysqli_real_query_method_handler = NULL;        //mysqli::real_query
zif_handler ori_mysqli_multi_query_handler = NULL;              //mysqli_multi_query
zif_handler ori_mysqli_multi_query_method_handler = NULL;       //mysqli::multi_query
zif_handler ori_mysqli_prepare_handler =NULL;                   //mysqli_prepare
zif_handler ori_mysqli_prepare_method_handler=NULL;             //mysqli::prepare
zif_handler ori_pdo_prepare_method_handler=NULL;                //pdo::prepare
zif_handler ori_pdo_exec_method_handler=NULL;                   //pdo::exec
zif_handler ori_mysqli_stmt_bind_param_handler = NULL;          //mysqli_stmt_bind_param
zif_handler ori_mysqli_stmt_bind_param_method_handler = NULL;   //mysqli_stmt::bind_param
zif_handler ori_pdostmt_bindParam_method_handler = NULL;        //pdo_stmt::bindParam
zif_handler ori_pdostmt_bindValue_method_handler = NULL;         //pdo_stm::bindValue
zif_handler ori_mysqli_execute_query_handler = NULL;            // mysqli_execute_query
zif_handler ori_mysqli_execute_query_method_handler = NULL;     // mysqli::execute_query
zif_handler ori_mysqli_stmt_execute_handler = NULL;             // mysqli_stmt_execute
zif_handler ori_mysqli_stmt_execute_method_handler = NULL;      // mysqli_stmt::execute
zif_handler ori_pdostmt_execute_method_handler = NULL;         // pdo_stmt::execute

zif_handler get_dbfunction_handler(char *scope_name, char *func_name);


zif_handler get_dbfunction_handler(char *scope_name, char *func_name){
    if(scope_name && func_name){
        if(strcmp(scope_name,"mysqli")== 0){
            if(strcmp(func_name,"query")==0){
                return ori_mysqli_query_method_handler;
            }if(strcmp(func_name,"execute_query")==0){
                return ori_mysqli_execute_query_method_handler;
            }else if(strcmp(func_name,"real_query")==0){
                return ori_mysqli_real_query_method_handler;
            }else if(strcmp(func_name,"multi_query")==0){
                return ori_mysqli_multi_query_method_handler;
            }else if(strcmp(func_name,"prepare")==0){
                return ori_mysqli_prepare_method_handler;
            }
        }else if(strcmp(scope_name,"PDO")==0){
            if(strcmp(func_name,"query")==0){
                return ori_pdo_query_method_handler; 
            }else if(strcmp(func_name,"prepare")==0){
                return ori_pdo_prepare_method_handler;
            }else if(strcmp(func_name,"exec")==0){
                return ori_pdo_exec_method_handler;
            }
        }else if(strcmp(scope_name,"mysqli_stmt")== 0){
            if(strcmp(func_name,"bind_param")==0){
                return ori_mysqli_stmt_bind_param_method_handler;
            }else if(strcmp(func_name,"execute")==0){
                return ori_mysqli_stmt_execute_method_handler;
            }
        }else if(strcmp(scope_name,"PDOStatement")==0){
            if(strcmp(func_name,"bindParam")==0){
                return ori_pdostmt_bindParam_method_handler;
            }if(strcmp(func_name,"bindValue")==0){
                return ori_pdostmt_bindValue_method_handler;
            }
            else if(strcmp(func_name,"execute")==0){
                return ori_pdostmt_execute_method_handler;
            }
        }    
    }else if(strcmp(func_name,"mysqli_query")==0){
        return ori_mysqli_query_handler;
    }else if(strcmp(func_name,"mysqli_execute_query")==0){
        return ori_mysqli_execute_query_handler;
    }else if(strcmp(func_name,"mysqli_real_query")==0){
        return ori_mysqli_real_query_handler;
    }else if(strcmp(func_name,"mysqli_multi_query")==0){
        return ori_mysqli_multi_query_handler;
    }else if(strcmp(func_name,"mysqli_prepare")==0){
        return ori_mysqli_prepare_handler;
    }else if(strcmp(func_name,"mysqli_stmt_bind_param")==0){
        return ori_mysqli_stmt_bind_param_handler; 
    }else if(strcmp(func_name,"mysqli_stmt_execute")==0){
        return ori_mysqli_stmt_execute_handler; 
    }
}

void query_handler(zend_execute_data *execute_data, zval *return_value){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif

    char *scope_name = NULL; 
    char *func_name_str=NULL, *class_method = NULL;
    zval *query = NULL;
    char *func_name = ZSTR_VAL(execute_data->func->common.function_name);
    
    if(execute_data->func && execute_data->func->common.scope && func_name){
        scope_name = ZSTR_VAL(execute_data->func->common.scope->name);
        // php_printf("%s::%s\n", scope_name, func_name);
        int length = strlen(scope_name) + strlen("::")+ strlen(func_name);
        class_method = emalloc(length+1);
        snprintf(class_method, length+1, "%s::%s", scope_name, func_name);
        func_name_str = class_method;
        query = ZEND_CALL_ARG(execute_data, 1);
    }else{
        // php_printf("%s\n", func_name);
        func_name_str = func_name;
        query = ZEND_CALL_ARG(execute_data, 2);
    }
    //early termination guard because the request does not come from fuzzer
    //no ZIMPAF_G(coverage_id)
    if (ZIMPAF_G(coverage_id) == NULL) {
        zif_handler dbfunction_handler = get_dbfunction_handler(scope_name, func_name);
        query = NULL;
        if(class_method != NULL){
            efree(class_method);
            class_method = NULL;
        }
        func_name_str = NULL;
        dbfunction_handler(INTERNAL_FUNCTION_PARAM_PASSTHRU);
        return;
    }
    char *query_str = Z_STRVAL_P(query);
    const char *filename = "Unknown";
    int lineno = 0;

    zend_execute_data *caller = execute_data->prev_execute_data; // Use execute_data
    if (caller && caller->func && caller->func->op_array.filename) {
        filename = ZSTR_VAL(caller->func->op_array.filename);
        lineno = caller->opline ? caller->opline->lineno : 0;
    }
    printf("Coverage-ID: %s\n", ZIMPAF_G(coverage_id));
    unsigned int is_query_literal = is_func_param_string_literal(query, execute_data);

    //store function traces to ZIMPAF_G(func_call_seq)  
    cJSON *func_call = cJSON_CreateObject();
    cJSON_AddStringToObject(func_call, "function_name", func_name_str);
    cJSON_AddStringToObject(func_call, "query", query_str);
    cJSON_AddNumberToObject(func_call, "sink_opline_type", is_query_literal);
    if((strcmp(func_name_str, "mysqli_prepare") == 0) || (strcmp(func_name_str, "mysqli::prepare") == 0) ||
        (strcmp(func_name_str, "PDO::prepare") == 0)){
        cJSON_AddNumberToObject(func_call, "prepare_func", 1);
    }else{
        cJSON_AddNumberToObject(func_call, "query_func", 1);   
    }
    cJSON_AddStringToObject(func_call, "filename", filename);
    cJSON_AddNumberToObject(func_call, "lineno", lineno);
    cJSON_AddItemToArray(ZIMPAF_G(func_call_seq), func_call);
    
    //execute original handler
    zif_handler dbfunction_handler = get_dbfunction_handler(scope_name, func_name);
    dbfunction_handler(INTERNAL_FUNCTION_PARAM_PASSTHRU);
   
    if( Z_TYPE_P(return_value) != IS_FALSE){
        zval *retval = return_value;
        while (Z_TYPE_P(retval) == IS_REFERENCE) {
            retval = Z_REFVAL_P(retval);
        }
        zend_string *param_str = NULL;
        if(Z_TYPE_P(retval ) == IS_OBJECT || Z_TYPE_P(retval) == IS_RESOURCE || Z_TYPE_P(retval ) == IS_ARRAY){
            switch (Z_TYPE_P(retval )) {
                case IS_OBJECT: {
                    // Use class name as a stand-in
                    zend_object *obj = Z_OBJ_P(retval);
                    param_str = zend_strpprintf(0, "%p", obj);          //print the address of object
                    break;
                }
                case IS_ARRAY:
                    zend_array *arr = Z_ARRVAL_P(retval);
                    param_str = zend_strpprintf(0, "%p", (void *)arr);  //print the address of object
                    break;
                case IS_RESOURCE:
                    zend_resource *res = Z_RES_P(retval);
                    param_str = zend_strpprintf(0, "%p", (void *)res);  //print the address of object
                    break;
                // default:
                //     param_str = zend_string_init("[unknown]", sizeof("[unknown]") - 1, 0);
                //     break;
            }
        }else{
            param_str = zval_get_string(retval);
        }
        cJSON_AddStringToObject(func_call, "return_value", ZSTR_VAL(param_str)); //print the address of object
        zend_string_release(param_str);
    }

    if (Z_TYPE_P(return_value) == IS_FALSE) {
        cJSON_AddStringToObject(func_call, "return_value", ""); 
        int error_no = -1;
        char *error = NULL;
        int sqlstate = -1;

        //this is used if PDO is active and error info can be used as indication.
        //error_info is destroyed after cJSON logging is performed.
        zval error_info;
        ZVAL_UNDEF(&error_info);
    
        if(scope_name != NULL && strcmp(scope_name,"PDO")==0){
            pdo_dbh_t *dbh = Z_PDO_DBH_P(ZEND_THIS); //assuming object is PDO object (zval *)
            if (!dbh || !dbh->driver_data) {
                printf("Invalid PDO connection");
                RETURN_FALSE;
            }
            //Log driver-specific error code and message
            if (dbh->error_code) {
                printf("SQLState: %s\n", dbh->error_code); //SQLSTATE
                sqlstate = atoi(dbh->error_code);
            }
            if (dbh->methods->fetch_err) {
                //Initiate error info as array to prepare a zval to hold error info
                //Don't forget destroying it after it and other pointers/data derive from it is use.
                array_init(&error_info);
                if (dbh->query_stmt) {
                    dbh->methods->fetch_err(dbh, dbh->query_stmt, &error_info);
                } else {
                    dbh->methods->fetch_err(dbh, NULL, &error_info);
                }
                zval *native_code = zend_hash_index_find(Z_ARRVAL(error_info), 0);
                zval *str_code = zend_hash_index_find(Z_ARRVAL(error_info), 1);
                zval *msg = zend_hash_index_find(Z_ARRVAL(error_info), 2);

                if (native_code) {
                    if (Z_TYPE_P(native_code) == IS_LONG) {
                        printf("Native error code: %ld\n", Z_LVAL_P(native_code));
                        error_no = Z_LVAL_P(native_code);
                    } else if (Z_TYPE_P(native_code) == IS_STRING) {
                        printf("Native error code: %s\n", Z_STRVAL_P(native_code));
                        error_no = atoi(Z_STRVAL_P(native_code));
                    }
                }
                if (str_code && Z_TYPE_P(str_code) == IS_STRING) {
                    printf("Error code string: %s\n", Z_STRVAL_P(str_code));
                    error = Z_STRVAL_P(str_code);
                }
                if (msg && Z_TYPE_P(msg) == IS_STRING) {
                    printf("Driver error message: %s\n", Z_STRVAL_P(msg));
                }
            }
        }else{
            zval *mysqli_link = NULL;
            MY_MYSQL *mysql = NULL;
            if(execute_data->func && execute_data->func->common.scope){
                mysqli_link = ZEND_THIS;
            }else{
                mysqli_link = ZEND_CALL_ARG(execute_data, 1);
            }           
            MYSQLI_FETCH_RESOURCE_CONN(mysql, mysqli_link, MYSQLI_STATUS_VALID);
            if (!mysql || !mysql->mysql || !mysql->mysql->data) {
                php_error_docref(NULL, E_WARNING, "Invalid MySQL connection");
                RETURN_FALSE;
            }
            //Log the error info
            error_no = mysql->mysql->data->error_info->error_no;
            error = mysql->mysql->data->error_info->error;
            sqlstate = atoi(mysql_sqlstate(mysql->mysql));
            printf("MySQL errno: %d\n", error_no);
            printf("MySQL error: %s\n", error);
            printf("MySQL state: %d\n", sqlstate);
        }
        cJSON *mysqli_error_log = cJSON_CreateObject();
        cJSON_AddStringToObject(mysqli_error_log, "function_name", func_name_str);
        cJSON_AddStringToObject(mysqli_error_log, "query", query_str);
        cJSON_AddNumberToObject(mysqli_error_log, "error_no", error_no);
        cJSON_AddStringToObject(mysqli_error_log, "error", error);
        cJSON_AddNumberToObject(mysqli_error_log,"sqlstate",sqlstate);
        cJSON_AddStringToObject(mysqli_error_log, "filename", filename);
        cJSON_AddNumberToObject(mysqli_error_log, "lineno", lineno);
        char *json_string = cJSON_PrintUnformatted(mysqli_error_log);

        //destroyed error_info array if PDO is active.
        if(Z_TYPE(error_info) == IS_ARRAY){
            zval_ptr_dtor(&error_info); //very crucial, don't forget to do this.
        }

        if(json_string!= NULL){
            printf("%s\n", json_string);
            if(ZIMPAF_G(coverage_id) != NULL){
                char mysqli_error_dir[] = "/shared-tmpfs/mysql-error-reports";
                unsigned int len_mysqli_error_fname = strlen(mysqli_error_dir) + strlen("/")+strlen(ZIMPAF_G(coverage_id))+strlen(".json")+1;
                char mysqli_error_fname[len_mysqli_error_fname];
                snprintf(mysqli_error_fname, len_mysqli_error_fname, "%s/%s.json", mysqli_error_dir, ZIMPAF_G(coverage_id));
                FILE *f = fopen(mysqli_error_fname, "w");
                if (f) {
                    fputs(json_string, f);
                    fputs("\n",f);
                    fclose(f);
                }
            }
            free(json_string); // Free the JSON string
        }
        cJSON_Delete(mysqli_error_log);
        mysqli_error_log = NULL;
    }
    
    if(class_method != NULL){
        efree(class_method);
        class_method = NULL;
    }
    func_name_str = NULL;
}

void generic_mysqlfunc_handler(zend_execute_data *execute_data, zval *return_value){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    // php_printf("Intercepted mysqli_stmt_bind_param\n");
    char *scope_name = NULL; 
    char *func_name_str=NULL, *class_method = NULL;
    char *func_name = ZSTR_VAL(execute_data->func->common.function_name);
    unsigned int num_args = ZEND_CALL_NUM_ARGS(execute_data); // Get number of arguments
    
    if(execute_data->func && execute_data->func->common.scope && func_name){
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

    if (ZIMPAF_G(coverage_id) == NULL) {
        zif_handler dbfunction_handler = get_dbfunction_handler(scope_name, func_name);
        if(class_method != NULL){
            efree(class_method);
            class_method = NULL;
        }
        func_name_str = NULL;
        dbfunction_handler(INTERNAL_FUNCTION_PARAM_PASSTHRU);
        return;
    }

    const char *filename = "Unknown";
    int lineno = 0;
    zend_execute_data *caller = execute_data->prev_execute_data; // Use execute_data
    if (caller && caller->func && caller->func->op_array.filename) {
        filename = ZSTR_VAL(caller->func->op_array.filename);
        lineno = caller->opline ? caller->opline->lineno : 0;
    }
    
    //concatenate all the parameters into single line to be efficiently use by fuzzer to define
    //relation between input and parameter, and also relation between (sequence of) functions in function trace 
    size_t buf_size = 512;
    char *buf = (char *) emalloc(buf_size);
    buf[0] ='\0';
    size_t len = 0;
    for (int i = 1; i <= num_args; i++) {
        zval *param = ZEND_CALL_ARG(execute_data, i);
        while (Z_TYPE_P(param) == IS_REFERENCE) {
            param = Z_REFVAL_P(param);
        }
        //zend_arg_info *arg_info = &op_array->arg_info[i - 1];
        //zend_string *param_name = arg_info->name;
        char *param_str = NULL;
        if(Z_TYPE_P(param) == IS_OBJECT || Z_TYPE_P(param) == IS_RESOURCE || Z_TYPE_P(param) == IS_ARRAY){
            switch (Z_TYPE_P(param)) {
                case IS_OBJECT: {
                    //Use class name as a stand-in
                    zend_object *obj = Z_OBJ_P(param);
                    param_str = emalloc(64); // Allocate memory for the string
                    snprintf(param_str, 64, "%p", (void *)obj);          //print the address of
                    break;
                }
                case IS_ARRAY:
                    param_str = concatenate_zval_array_into_string(param);
                    break;
                case IS_RESOURCE:
                    zend_resource *res = Z_RES_P(param);
                    param_str = emalloc(64); // Allocate memory for the string
                    snprintf(param_str, 64, "%p", (void *)res);  //print the address of resource
                    break;
                // default:
                //     param_str = zend_string_init("[unknown]", sizeof("[unknown]") - 1, 0);
                //     break;
            }
        }else{
            zend_string *tmp_str = zval_try_get_string(param);
            if (UNEXPECTED(!tmp_str)) {
                param_str = estrdup("");
            } else {
                param_str = estrdup(ZSTR_VAL(tmp_str));
                zend_string_release(tmp_str);
            }
        }
        if(param_str){
            size_t param_str_len = strlen(param_str);
            size_t total_len = len + param_str_len + 1;
            if(total_len >= buf_size){
                buf_size = total_len + 256;
                buf = (char *) erealloc(buf, buf_size);
            }
            memcpy(buf + len, param_str, param_str_len);
            len += param_str_len;
            efree(param_str); //Free the parameter string after use
        }
            if (i < num_args) {
                buf[len++] = '_';
            } else {
                buf[len] = '\0';
            }
    }
    //logging
    cJSON *func_call = cJSON_CreateObject();
    cJSON_AddStringToObject(func_call, "function_name", func_name_str);
    cJSON_AddStringToObject(func_call, "parameters", buf); //parameters combined with "_".
    cJSON_AddNumberToObject(func_call, "num_params", num_args);
    cJSON_AddNumberToObject(func_call, "bind_execute_func", 1); // Indicate this is a bind/exec function
    cJSON_AddStringToObject(func_call, "filename", filename);
    cJSON_AddNumberToObject(func_call, "lineno", lineno);
    cJSON_AddItemToArray(ZIMPAF_G(func_call_seq), func_call);

    if (Z_TYPE_P(ZEND_THIS) == IS_OBJECT) {
        zend_object *obj = Z_OBJ_P(ZEND_THIS);
        zend_string *object_ptr = zend_strpprintf(0, "%p", obj);
        cJSON_AddStringToObject(func_call, "object_pointer", ZSTR_VAL(object_ptr));
        zend_string_release(object_ptr);
    }
    if (buf) {
        efree(buf);
    }

    if(execute_data->func && execute_data->func->common.scope){
        if(strcmp(scope_name,"mysqli_stmt")== 0){
            if(strcmp(func_name,"bind_param")==0){
                ori_mysqli_stmt_bind_param_method_handler(INTERNAL_FUNCTION_PARAM_PASSTHRU);
            }else if(strcmp(func_name,"execute")==0){
                ori_mysqli_stmt_execute_method_handler(INTERNAL_FUNCTION_PARAM_PASSTHRU);
            }
        }else if(strcmp(scope_name,"PDOStatement")==0){
            if(strcmp(func_name,"bindParam")==0){
                ori_pdostmt_bindParam_method_handler(INTERNAL_FUNCTION_PARAM_PASSTHRU);
            }if(strcmp(func_name,"bindValue")==0){
                ori_pdostmt_bindValue_method_handler(INTERNAL_FUNCTION_PARAM_PASSTHRU);
            }
            else if(strcmp(func_name,"execute")==0){
                ori_pdostmt_execute_method_handler(INTERNAL_FUNCTION_PARAM_PASSTHRU);
            }
        }
    }else if(strcmp(func_name,"mysqli_stmt_bind_param")==0){
        ori_mysqli_stmt_bind_param_handler(INTERNAL_FUNCTION_PARAM_PASSTHRU);
    }else if(strcmp(func_name,"mysqli_stmt_execute")==0){
        ori_mysqli_stmt_execute_handler(INTERNAL_FUNCTION_PARAM_PASSTHRU);
    }

    char *retval_str = get_return_value_string(return_value);
    cJSON_AddStringToObject(func_call, "return_value", retval_str);
    efree(retval_str); 
    
    //execute original handler
    zif_handler dbfunction_handler = get_dbfunction_handler(scope_name, func_name);
    dbfunction_handler(INTERNAL_FUNCTION_PARAM_PASSTHRU);

    if(class_method != NULL){
        efree(class_method);
        class_method = NULL;
    }
    func_name_str = NULL;   
}

void hook_mysqli_query() {
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    // Hook the function
    zend_function *ori_mysqli_query_func = zend_hash_str_find_ptr(CG(function_table), "mysqli_query", sizeof("mysqli_query")-1);
    if (ori_mysqli_query_func && ori_mysqli_query_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_mysqli_query_handler = ori_mysqli_query_func->internal_function.handler;
        ori_mysqli_query_func->internal_function.handler = query_handler;
    }
}

void hook_mysqli_query_cm(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_class_entry *mysqli_ce = zend_hash_str_find_ptr(CG(class_table), "mysqli", sizeof("mysqli")-1);
    if (mysqli_ce) {
        zend_function *mysqli_query_method = zend_hash_str_find_ptr(&mysqli_ce->function_table, "query", sizeof("query") - 1);
        if (mysqli_query_method && mysqli_query_method->type == ZEND_INTERNAL_FUNCTION) {
            ori_mysqli_query_method_handler = mysqli_query_method->internal_function.handler;
            mysqli_query_method->internal_function.handler = (zif_handler)query_handler;
        }
    }
}

void hook_mysqli_execute_query() {
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_mysqli_execute_query_func = zend_hash_str_find_ptr(CG(function_table), "mysqli_execute_query", sizeof("mysqli_execute_query")-1);
    if (ori_mysqli_execute_query_func && ori_mysqli_execute_query_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_mysqli_execute_query_handler = ori_mysqli_execute_query_func->internal_function.handler;
        ori_mysqli_execute_query_func->internal_function.handler = (zif_handler) query_handler;
    }
}

void hook_mysqli_execute_query_cm(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_class_entry *mysqli_ce = zend_hash_str_find_ptr(CG(class_table), "mysqli", sizeof("mysqli")-1);
    if (mysqli_ce) {
        zend_function *mysqli_execute_query_method = zend_hash_str_find_ptr(&mysqli_ce->function_table, "execute_query", sizeof("execute_query") - 1);
        if (mysqli_execute_query_method && mysqli_execute_query_method->type == ZEND_INTERNAL_FUNCTION) {
            ori_mysqli_execute_query_method_handler = mysqli_execute_query_method->internal_function.handler;
            mysqli_execute_query_method->internal_function.handler = (zif_handler) query_handler;
        }
    }
}

void hook_pdo_query_cm(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
		ZEND_TSRMLS_CACHE_UPDATE();
	#endif
    zend_class_entry *pdo_ce = zend_hash_str_find_ptr(CG(class_table), "pdo", sizeof("pdo")-1);
    if (pdo_ce) {
        zend_function *pdo_query_method = zend_hash_str_find_ptr(&pdo_ce->function_table, "query", sizeof("query") - 1);
        // pdo_query_method = zend_hash_str_find_ptr(&pdo_query_ce->function_table, ZEND_STRL("query"));
        if (pdo_query_method && pdo_query_method->type == ZEND_INTERNAL_FUNCTION) {
            ori_pdo_query_method_handler = pdo_query_method->internal_function.handler;
            pdo_query_method->internal_function.handler = query_handler;
            // php_printf("PDO::query handler is installed.\n");
        }
    }
}

void hook_mysqli_real_query() {
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    // Hook the function
    zend_function *ori_mysqli_real_query_func = zend_hash_str_find_ptr(CG(function_table), "mysqli_real_query", sizeof("mysqli_real_query")-1);
    if (ori_mysqli_real_query_func && ori_mysqli_real_query_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_mysqli_real_query_handler = ori_mysqli_real_query_func->internal_function.handler;
        ori_mysqli_real_query_func->internal_function.handler = query_handler;
    }
}

void hook_mysqli_real_query_cm(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_class_entry *mysqli_ce = zend_hash_str_find_ptr(CG(class_table), "mysqli", sizeof("mysqli")-1);
    if (mysqli_ce) {
        zend_function *mysqli_real_query_method = zend_hash_str_find_ptr(&mysqli_ce->function_table, "real_query", sizeof("real_query") - 1);
        if (mysqli_real_query_method && mysqli_real_query_method->type == ZEND_INTERNAL_FUNCTION) {
            ori_mysqli_real_query_method_handler = mysqli_real_query_method->internal_function.handler;
            mysqli_real_query_method->internal_function.handler = (zif_handler)query_handler;
        }
    }
}

void hook_mysqli_multi_query() {
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    // Hook the function
    zend_function *ori_mysqli_multi_query_func = zend_hash_str_find_ptr(CG(function_table), "mysqli_multi_query", sizeof("mysqli_multi_query")-1);
    if (ori_mysqli_multi_query_func && ori_mysqli_multi_query_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_mysqli_multi_query_handler = ori_mysqli_multi_query_func->internal_function.handler;
        ori_mysqli_multi_query_func->internal_function.handler = query_handler;
    }
}

void hook_mysqli_multi_query_cm(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_class_entry *mysqli_ce = zend_hash_str_find_ptr(CG(class_table), "mysqli", sizeof("mysqli")-1);
    if (mysqli_ce) {
        zend_function *mysqli_multi_query_method = zend_hash_str_find_ptr(&mysqli_ce->function_table, "multi_query", sizeof("multi_query") - 1);
        if (mysqli_multi_query_method && mysqli_multi_query_method->type == ZEND_INTERNAL_FUNCTION) {
            ori_mysqli_multi_query_method_handler = mysqli_multi_query_method->internal_function.handler;
            mysqli_multi_query_method->internal_function.handler = (zif_handler)query_handler;
        }
    }
}

void hook_mysqli_prepare() {
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_mysqli_prepare_func = zend_hash_str_find_ptr(CG(function_table), "mysqli_prepare", sizeof("mysqli_prepare")-1);
    if (ori_mysqli_prepare_func && ori_mysqli_prepare_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_mysqli_prepare_handler = ori_mysqli_prepare_func->internal_function.handler;
        ori_mysqli_prepare_func->internal_function.handler = query_handler;
    }
}

void hook_mysqli_prepare_cm(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_class_entry *mysqli_ce = zend_hash_str_find_ptr(CG(class_table), "mysqli", sizeof("mysqli")-1);
    if (mysqli_ce) {
        zend_function *mysqli_prepare_method = zend_hash_str_find_ptr(&mysqli_ce->function_table, "prepare", sizeof("prepare") - 1);
        if (mysqli_prepare_method && mysqli_prepare_method->type == ZEND_INTERNAL_FUNCTION) {
            ori_mysqli_prepare_method_handler = mysqli_prepare_method->internal_function.handler;
            mysqli_prepare_method->internal_function.handler = (zif_handler)query_handler;
        }
    }
}

void hook_pdo_prepare_cm(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
		ZEND_TSRMLS_CACHE_UPDATE();
	#endif
    zend_class_entry *pdo_ce = zend_hash_str_find_ptr(CG(class_table), "pdo", sizeof("pdo")-1);
    if (pdo_ce) {
        zend_function *pdo_prepare_method = zend_hash_str_find_ptr(&pdo_ce->function_table, "prepare", sizeof("prepare") - 1);
        if (pdo_prepare_method && pdo_prepare_method->type == ZEND_INTERNAL_FUNCTION) {
            ori_pdo_prepare_method_handler = pdo_prepare_method->internal_function.handler;
            pdo_prepare_method->internal_function.handler = query_handler;
            // php_printf("PDO::query handler is installed.\n");
        }
    }
}

void hook_pdo_exec_cm(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
		ZEND_TSRMLS_CACHE_UPDATE();
	#endif
    zend_class_entry *pdo_ce = zend_hash_str_find_ptr(CG(class_table), "pdo", sizeof("pdo")-1);
    if (pdo_ce) {
        zend_function *pdo_exec_method = zend_hash_str_find_ptr(&pdo_ce->function_table, "exec", sizeof("exec") - 1);
        if (pdo_exec_method && pdo_exec_method->type == ZEND_INTERNAL_FUNCTION) {
            ori_pdo_exec_method_handler = pdo_exec_method->internal_function.handler;
            pdo_exec_method->internal_function.handler = query_handler;
            // php_printf("PDO::query handler is installed.\n");
        }
    }
}

void hook_mysqli_stmt_bind_param() {
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_mysqli_stmt_bind_param_func = zend_hash_str_find_ptr(CG(function_table), "mysqli_stmt_bind_param", sizeof("mysqli_stmt_bind_param")-1);
    if (ori_mysqli_stmt_bind_param_func && ori_mysqli_stmt_bind_param_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_mysqli_stmt_bind_param_handler = ori_mysqli_stmt_bind_param_func->internal_function.handler;
        ori_mysqli_stmt_bind_param_func->internal_function.handler = (zif_handler) generic_mysqlfunc_handler;
    }
}

void hook_mysqli_stmt_bind_param_cm(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_class_entry *mysqli_stmt_ce = zend_hash_str_find_ptr(CG(class_table), "mysqli_stmt", sizeof("mysqli_stmt")-1);
    if (mysqli_stmt_ce) {
        zend_function *mysqli_stmt_bind_param_method = zend_hash_str_find_ptr(&mysqli_stmt_ce->function_table, "bind_param", sizeof("bind_param") - 1);
        if (mysqli_stmt_bind_param_method && mysqli_stmt_bind_param_method->type == ZEND_INTERNAL_FUNCTION) {
            ori_mysqli_stmt_bind_param_method_handler = mysqli_stmt_bind_param_method->internal_function.handler;
            mysqli_stmt_bind_param_method->internal_function.handler = (zif_handler)generic_mysqlfunc_handler;
        }
    }
}

void hook_pdostmt_bindParam_cm(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
		ZEND_TSRMLS_CACHE_UPDATE();
	#endif
    zend_class_entry *pdostatement_ce = zend_hash_str_find_ptr(CG(class_table), "pdostatement", sizeof("pdostatement")-1);
    if (pdostatement_ce) {
        zend_function *pdostmt_bindParam_method = zend_hash_str_find_ptr(&pdostatement_ce->function_table, "bindparam", sizeof("bindparam") - 1);
        if (pdostmt_bindParam_method && pdostmt_bindParam_method->type == ZEND_INTERNAL_FUNCTION) {
            ori_pdostmt_bindParam_method_handler = pdostmt_bindParam_method->internal_function.handler;
            pdostmt_bindParam_method->internal_function.handler = (zif_handler)generic_mysqlfunc_handler;
            // php_printf("PDO::query handler is installed.\n");
        }
    }
}

void hook_pdostmt_bindValue_cm(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
		ZEND_TSRMLS_CACHE_UPDATE();
	#endif
    zend_class_entry *pdostatement_ce = zend_hash_str_find_ptr(CG(class_table), "pdostatement", sizeof("pdostatement")-1);
    if (pdostatement_ce) {
        zend_function *pdostmt_bindValue_method = zend_hash_str_find_ptr(&pdostatement_ce->function_table, "bindvalue", sizeof("bindvalue") - 1);
        if (pdostmt_bindValue_method && pdostmt_bindValue_method->type == ZEND_INTERNAL_FUNCTION) {
            ori_pdostmt_bindValue_method_handler = pdostmt_bindValue_method->internal_function.handler;
            pdostmt_bindValue_method->internal_function.handler = (zif_handler)generic_mysqlfunc_handler;
        }
    }
}

void hook_mysqli_stmt_execute() {
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_mysqli_stmt_execute_func = zend_hash_str_find_ptr(CG(function_table), "mysqli_stmt_execute", sizeof("mysqli_stmt_execute")-1);
    if (ori_mysqli_stmt_execute_func && ori_mysqli_stmt_execute_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_mysqli_stmt_execute_handler = ori_mysqli_stmt_execute_func->internal_function.handler;
        ori_mysqli_stmt_execute_func->internal_function.handler = (zif_handler) generic_mysqlfunc_handler;
    }
}

void hook_mysqli_stmt_execute_cm(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_class_entry *mysqli_stmt_ce = zend_hash_str_find_ptr(CG(class_table), "mysqli_stmt", sizeof("mysqli_stmt")-1);
    if (mysqli_stmt_ce) {
        zend_function *mysqli_stmt_execute_method = zend_hash_str_find_ptr(&mysqli_stmt_ce->function_table, "execute", sizeof("execute") - 1);
        if (mysqli_stmt_execute_method && mysqli_stmt_execute_method->type == ZEND_INTERNAL_FUNCTION) {
            ori_mysqli_stmt_execute_method_handler = mysqli_stmt_execute_method->internal_function.handler;
            mysqli_stmt_execute_method->internal_function.handler = (zif_handler)generic_mysqlfunc_handler;
        }
    }
}

void hook_pdostmt_execute_cm(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
		ZEND_TSRMLS_CACHE_UPDATE();
	#endif
    zend_class_entry *pdostatement_ce = zend_hash_str_find_ptr(CG(class_table), "pdostatement", sizeof("pdostatement")-1);
    if (pdostatement_ce) {
        zend_function *pdostmt_execute_method = zend_hash_str_find_ptr(&pdostatement_ce->function_table, "execute", sizeof("execute") - 1);
        if (pdostmt_execute_method && pdostmt_execute_method->type == ZEND_INTERNAL_FUNCTION) {
            ori_pdostmt_execute_method_handler = pdostmt_execute_method->internal_function.handler;
            pdostmt_execute_method->internal_function.handler = (zif_handler)generic_mysqlfunc_handler;
            // php_printf("PDO::query handler is installed.\n");
        }
    }
}
