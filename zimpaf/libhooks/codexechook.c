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


#include "../include/codexechook.h"
#include "../include/utils.h"
#include "../php_zimpaf.h"
#include "../../standard/exec.h"
#include "ext/standard/proc_open.h"
#include <sys/types.h>
#include <sys/wait.h>


zif_handler ori_assert_handler = NULL;                  //assert
zif_handler ori_system_handler = NULL;                  //system
zif_handler ori_exec_handler = NULL;                    //exec      
zif_handler ori_passthru_handler = NULL;                //passthru
zif_handler ori_shell_exec_handler = NULL;              //shell_exec
zif_handler ori_popen_handler = NULL;                   //popen
zif_handler ori_proc_open_handler = NULL;               //proc_open

// int call_php_exec(INTERNAL_FUNCTION_PARAMETERS, int mode);
int call_popen_libc(INTERNAL_FUNCTION_PARAMETERS);
static int fake_php_exec_ex(INTERNAL_FUNCTION_PARAMETERS, int mode);
typedef struct _proc_open_trace{
    int error_info; 
    int len_buf;
    char buf[1024];
} proc_open_trace;
void is_failed_proc_open(INTERNAL_FUNCTION_PARAMETERS, proc_open_trace *trace);
zif_handler get_original_code_exec_handler(char *func_name_str);

zif_handler get_original_code_exec_handler(char *func_name_str){
    if(strcmp(func_name_str,"assert")==0){
        return ori_assert_handler;
    }else if(strcmp(func_name_str,"system") == 0){
        return ori_system_handler;      
    }else if(strcmp(func_name_str,"exec")==0){
        return ori_exec_handler;
    }else if(strcmp(func_name_str,"passthru")==0){
        return ori_passthru_handler; 
    }else if(strcmp(func_name_str,"shell_exec")==0){
        return ori_shell_exec_handler; 
    }else if(strcmp(func_name_str,"popen")==0){
        return ori_popen_handler;         
    }else if(strcmp(func_name_str,"proc_open")==0){
        return ori_proc_open_handler; 
    }
}

void generic_code_execution_handler(zend_execute_data *execute_data, zval *return_value){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    char *func_name = ZSTR_VAL(execute_data->func->common.function_name);
    unsigned int num_args = ZEND_CALL_NUM_ARGS(execute_data); // Get number of arguments
    unsigned int i;
    char *func_name_str=NULL, *class_method = NULL;

    char *scope_name = NULL;
    if(execute_data->func && execute_data->func->common.scope && func_name){
        scope_name = ZSTR_VAL(execute_data->func->common.scope->name);
        // php_printf("%s::%s\n", scope_name, func_name);
        int length = strlen(scope_name) + strlen("::")+ strlen(func_name);
        class_method = emalloc(length+1);
        snprintf(class_method, length+1, "%s::%s", scope_name, func_name);
        func_name_str = class_method;
    }else if(func_name){
        // php_printf("%s\n", func_name);
        func_name_str = func_name;
    }

    //early termination guard because the request does not come from fuzzer
    //no ZIMPAF_G(coverage_id)
    if (ZIMPAF_G(coverage_id) == NULL) {
        zif_handler code_exec_handler = get_original_code_exec_handler(func_name_str);
        if(class_method != NULL){
            efree(class_method);
            class_method = NULL;
        }
        func_name_str = NULL;
        code_exec_handler(INTERNAL_FUNCTION_PARAM_PASSTHRU);
        return;
    }

    const char *filename = "Unknown";
    int lineno = 0;
    zend_execute_data *caller = execute_data->prev_execute_data; // Use execute_data
    if (caller && caller->func && caller->func->op_array.filename) {
        filename = ZSTR_VAL(caller->func->op_array.filename);
        lineno = caller->opline ? caller->opline->lineno : 0;
    }
    // php_printf("%s:%d\n", filename, lineno);
    zval *param = ZEND_CALL_ARG(execute_data, 1);
    unsigned int sink_opline_type = is_func_param_string_literal(param, execute_data);

    char *command = Z_STRVAL_P(ZEND_CALL_ARG(execute_data, 1));

    cJSON *func_call = cJSON_CreateObject();
    cJSON_AddStringToObject(func_call, "function_name", func_name_str);
    cJSON_AddStringToObject(func_call, "command", command );
    cJSON_AddNumberToObject(func_call, "sink_opline_type", sink_opline_type);
    cJSON_AddStringToObject(func_call, "filename", filename);
    cJSON_AddNumberToObject(func_call, "lineno", lineno);
    cJSON_AddItemToArray(ZIMPAF_G(func_call_seq), func_call);

    int is_error = 0;
    zval *result_code_zv = NULL;
    FILE *in = NULL;
    proc_open_trace trace;
    zval tmp_exit_code;
    
    if(strcmp(func_name_str,"assert")==0){
        ori_assert_handler(INTERNAL_FUNCTION_PARAM_PASSTHRU);
    }else if(strcmp(func_name_str,"system") == 0){
        is_error = fake_php_exec_ex(INTERNAL_FUNCTION_PARAM_PASSTHRU,1); //call fake_php_exec_ex with param=return_value
    }else if(strcmp(func_name_str,"exec")==0){  
        // printf("fake php_exec is called");
        is_error = fake_php_exec_ex(INTERNAL_FUNCTION_PARAM_PASSTHRU,0); 
    }else if(strcmp(func_name_str,"passthru")==0){
        is_error = fake_php_exec_ex(INTERNAL_FUNCTION_PARAM_PASSTHRU,3); 
    }else if(strcmp(func_name_str,"shell_exec")==0){
        //call exec instead since no way we know shell_exec success or fail
        is_error = fake_php_exec_ex(INTERNAL_FUNCTION_PARAM_PASSTHRU,0); 
        if(is_error == 0){
            ori_shell_exec_handler(INTERNAL_FUNCTION_PARAM_PASSTHRU);
        }
    }else if(strcmp(func_name_str,"popen")==0){
        ori_popen_handler(INTERNAL_FUNCTION_PARAM_PASSTHRU);
        zend_resource *res= Z_RES_P(return_value);
        php_stream *stream = (php_stream *) zend_fetch_resource_ex(return_value,"stream",php_file_le_stream());
        if (!stream){
            is_error = 1;
        }
    }else if(strcmp(func_name_str,"proc_open")==0){
        ori_proc_open_handler(INTERNAL_FUNCTION_PARAM_PASSTHRU);
        if(Z_TYPE_P(return_value) == IS_FALSE){
            is_error = 1;
        }else{
            is_failed_proc_open(INTERNAL_FUNCTION_PARAM_PASSTHRU, &trace);
            is_error = trace.error_info;
            printf("proc_open is_error = %d\n",trace.error_info );
        }
    }
    char *retval_str = get_return_value_string(return_value);
    cJSON_AddStringToObject(func_call, "return_value", retval_str);
    efree(retval_str);

    // if (is_error) {
    if(Z_TYPE_P(return_value) == IS_FALSE || is_error != 0){
        printf("Error occurred in function %s\n", func_name_str);
        cJSON *codexec_error_log = cJSON_CreateObject();
        cJSON_AddStringToObject(codexec_error_log, "function_name", func_name_str);
        cJSON_AddStringToObject(codexec_error_log, "command", command);
        if(strcmp(func_name, "proc_open") == 0){
            if(trace.len_buf > 0){
                cJSON_AddStringToObject(codexec_error_log, "error", trace.buf);
                printf("proc_open error: %s\n", trace.buf);
            }else{
                cJSON_AddStringToObject(codexec_error_log, "error", "");
            }
        }else{
            cJSON_AddStringToObject(codexec_error_log, "error", Z_STRVAL_P(return_value));
        }
        cJSON_AddStringToObject(codexec_error_log, "filename", filename);
        cJSON_AddNumberToObject(codexec_error_log, "lineno", lineno);
        
        char *json_string = cJSON_PrintUnformatted(codexec_error_log); //print as json line

        if(json_string!= NULL){
            printf("%s\n", json_string);
            if(ZIMPAF_G(coverage_id) != NULL){
                printf("Coverage ID: %s\n", ZIMPAF_G(coverage_id));
                char codexec_error_dir[] = "/shared-tmpfs/shell-error-reports";
                unsigned int len_codexec_error_fname = strlen(codexec_error_dir) + strlen("/")+strlen(ZIMPAF_G(coverage_id))+strlen(".json")+1;
                char codexec_error_fname[len_codexec_error_fname];
                snprintf(codexec_error_fname, len_codexec_error_fname, "%s/%s.json", codexec_error_dir, ZIMPAF_G(coverage_id));
                FILE *f = fopen(codexec_error_fname, "w");
                if (f) {
                    printf("Writing error log to: %s\n", codexec_error_fname);
                    fputs(json_string, f);
                    fputs("\n",f);  //enable printing as json line
                    fflush(f);
                    printf("Error log written successfully.\n");
                    fclose(f);
                }else {
                    // THIS IS THE CRITICAL PART: Report the fopen failure to PHP's error log
                    char *open_error_str = strerror(errno);
                    php_error_docref(NULL, E_WARNING, "Custom log: Failed to open file '%s': %s",
                    codexec_error_fname, open_error_str);
                }
            }
            free(json_string); // Free the JSON string
        }
        cJSON_Delete(codexec_error_log);
    }

    if(class_method != NULL){
        efree(class_method);
        class_method = NULL;
    }
    func_name_str = NULL;
}

static int fake_php_exec_ex(INTERNAL_FUNCTION_PARAMETERS, int mode) {
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
	char *cmd;
	size_t cmd_len;
	zval *ret_code=NULL, *ret_array=NULL;
	int ret;

	ZEND_PARSE_PARAMETERS_START(1, (mode ? 2 : 3))
		Z_PARAM_STRING(cmd, cmd_len)
		Z_PARAM_OPTIONAL
		if (!mode) {
			Z_PARAM_ZVAL(ret_array)
		}
		Z_PARAM_ZVAL(ret_code)
	ZEND_PARSE_PARAMETERS_END_EX(return -1);

	if (!cmd_len) {
		zend_argument_value_error(1, "cannot be empty");
		ret = 1;
	}
	if (strlen(cmd) != cmd_len) {
		zend_argument_value_error(1, "must not contain any null bytes");
		ret = 1;
	}

	if (!ret_array) {
		ret = php_exec(mode, cmd, NULL, return_value);
	} else {
		if (Z_TYPE_P(Z_REFVAL_P(ret_array)) == IS_ARRAY) {
			ZVAL_DEREF(ret_array);
			SEPARATE_ARRAY(ret_array);
		} else {
			ret_array = zend_try_array_init(ret_array);
			if (!ret_array) {
				ret = 1;
			}
		}

		ret = php_exec(2, cmd, ret_array, return_value);
	}
	if (ret_code) {
		ZEND_TRY_ASSIGN_REF_LONG(ret_code, ret);
	}
    return ret;
}

int call_popen_libc(INTERNAL_FUNCTION_PARAMETERS){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif

    char *command, *mode;
	size_t command_len, mode_len;
	FILE *fp;
    int is_error = 0;

    ZEND_PARSE_PARAMETERS_START(2, 2)
		Z_PARAM_PATH(command, command_len)
		Z_PARAM_STRING(mode, mode_len)
	ZEND_PARSE_PARAMETERS_END_EX(return FAILURE);

    fp = popen(command, mode);
    int status = pclose(fp);
    if (!WIFEXITED(status) || WEXITSTATUS(status) != 0) {
        is_error = 1;
    }
    return is_error;
}

void is_failed_proc_open(INTERNAL_FUNCTION_PARAMETERS,proc_open_trace *trace){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    printf("is_failed_proc_open_is_called\n");
    zend_string *command_str = NULL;
	HashTable *command_ht = NULL;
	HashTable *descriptorspec = NULL; /* Mandatory argument */
	zval *pipes = NULL;               /* Mandatory argument */
	char *cwd = NULL;                                /* Optional argument */
	size_t cwd_len = 0;                              /* Optional argument */
	zval *environment = NULL, *other_options = NULL; /* Optional arguments */

    ZEND_PARSE_PARAMETERS_START(3, 6)
		Z_PARAM_ARRAY_HT_OR_STR(command_ht, command_str)
		Z_PARAM_ARRAY_HT(descriptorspec)
		Z_PARAM_ZVAL(pipes)
		Z_PARAM_OPTIONAL
		Z_PARAM_STRING_OR_NULL(cwd, cwd_len)
		Z_PARAM_ARRAY_OR_NULL(environment)
		Z_PARAM_ARRAY_OR_NULL(other_options)
	ZEND_PARSE_PARAMETERS_END();

    int le_proc_open = Z_RES_P(return_value)->type;
    php_process_handle *proc = NULL;

    trace->error_info = 0;
    trace->len_buf = 0;
    if(Z_TYPE_P(return_value) == IS_RESOURCE) {
        proc = (php_process_handle *) zend_fetch_resource_ex(return_value, "process", le_proc_open);
        if(proc){
            printf("from inside is_failed_proc_open-proc_open: %s\n", ZSTR_VAL(proc->command));
        }
    }
    
    int exitcode = 0;
    int wstatus=0;
    printf("getting the pid via wait_pid\n");
    // pid_t wait_pid = waitpid(proc->child, &wstatus, WNOHANG|WUNTRACED);
    pid_t wait_pid = waitpid(proc->child, &wstatus, 0);
    if (wait_pid == proc->child && WIFEXITED(wstatus)) {
		exitcode = WEXITSTATUS(wstatus);
        printf("proc_open: child process exited with code %d\n", exitcode);
    }
    printf("proc_open: getting exit_code\n");
    if(exitcode != 0){
        trace->error_info = 1;
        printf("proc_open: trace->error_info = %d\n", trace->error_info );
    }

    if (proc->npipes > 2 && proc->pipes[2]) {
        char buf[1024];
        size_t len =0;
        php_stream *stream;
        stream = (php_stream *)zend_fetch_resource(proc->pipes[2], "stream", php_file_le_stream());
        php_stream_set_option(stream, PHP_STREAM_OPTION_BLOCKING, 0, NULL);
        len = php_stream_read(stream, buf, sizeof(buf) - 1);
        if (len > 0) {
            // php_printf("Error output from proc_open: %s\n", buf);
            trace->error_info = 1;
            trace->len_buf = len;
            strncpy(trace->buf, buf, sizeof(trace->buf) - 1);
            trace->buf[sizeof(trace->buf) - 1] = '\0'; // Ensure null-termination
            printf("Error output: %s\n", trace->buf);
        }
    }
}

void hook_assert(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_assert_func = zend_hash_str_find_ptr(CG(function_table), "assert", sizeof("assert")-1);
    if (ori_assert_func && ori_assert_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_assert_handler = ori_assert_func->internal_function.handler;
        ori_assert_func->internal_function.handler = (zif_handler)generic_code_execution_handler;
    }
}
void hook_system(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_system_func = zend_hash_str_find_ptr(CG(function_table), "system", sizeof("system")-1);
    if (ori_system_func && ori_system_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_system_handler = ori_system_func->internal_function.handler;
        ori_system_func->internal_function.handler = (zif_handler)generic_code_execution_handler;
    }
}
void hook_exec(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_exec_func = zend_hash_str_find_ptr(CG(function_table), "exec", sizeof("exec")-1);
    if (ori_exec_func && ori_exec_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_exec_handler = ori_exec_func->internal_function.handler;
        ori_exec_func->internal_function.handler = (zif_handler)generic_code_execution_handler;
    }
}
void hook_passthru(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_passthru_func = zend_hash_str_find_ptr(CG(function_table), "passthru", sizeof("passthru")-1);
    if (ori_passthru_func && ori_passthru_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_passthru_handler = ori_passthru_func->internal_function.handler;
        ori_passthru_func->internal_function.handler = (zif_handler)generic_code_execution_handler;
    }
}
void hook_shell_exec(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_shell_exec_func = zend_hash_str_find_ptr(CG(function_table), "shell_exec", sizeof("shell_exec")-1);
    if (ori_shell_exec_func && ori_shell_exec_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_shell_exec_handler = ori_shell_exec_func->internal_function.handler;
        ori_shell_exec_func->internal_function.handler = (zif_handler)generic_code_execution_handler;
    }
}
void hook_popen(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_popen_func = zend_hash_str_find_ptr(CG(function_table), "popen", sizeof("popen")-1);
    if (ori_popen_func && ori_popen_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_popen_handler = ori_popen_func->internal_function.handler;
        ori_popen_func->internal_function.handler = (zif_handler)generic_code_execution_handler;
    }
}
void hook_proc_open(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_proc_open_func = zend_hash_str_find_ptr(CG(function_table), "proc_open", sizeof("proc_open")-1);
    if (ori_proc_open_func && ori_proc_open_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_proc_open_handler = ori_proc_open_func->internal_function.handler;
        ori_proc_open_func->internal_function.handler = (zif_handler)generic_code_execution_handler;
    }
}
