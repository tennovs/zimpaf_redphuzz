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


#include "../include/dirtravshook.h"
#include "../include/utils.h"
#include "../php_zimpaf.h"
   
zif_handler ori_chgrp_handler = NULL;                 //chgrp
zif_handler ori_chown_handler = NULL;                   //chown
zif_handler ori_chmod_handler = NULL;                   //chmod
zif_handler ori_copy_handler = NULL;                    //copy
zif_handler ori_delete_handler = NULL;                  //delete
zif_handler ori_dirname_handler = NULL;                 //dirname
zif_handler ori_file_handler = NULL;                    //file
zif_handler ori_file_get_contents_handler = NULL;       //file_get_contents
zif_handler ori_fopen_handler = NULL;                   //fopen
zif_handler ori_glob_handler = NULL;                    //glob
zif_handler ori_lchgrp_handler = NULL;                //lchgrp
zif_handler ori_lchown_handler = NULL;                  //lchown
zif_handler ori_link_handler = NULL;                    //link
zif_handler ori_mkdir_handler = NULL;                   //mkdir
zif_handler ori_move_uploaded_file_handler = NULL;      //move_uploaded_file
zif_handler ori_parse_ini_file_handler = NULL;          //parse_ini_file
zif_handler ori_parse_ini_string_handler = NULL;        //parse_ini_string
zif_handler ori_pathinfo_handler = NULL;                //pathinfo
zif_handler ori_readfile_handler = NULL;                //readfile
zif_handler ori_rename_handler = NULL;                  //rename
zif_handler ori_rmdir_handler = NULL;                   //rmdir
zif_handler ori_stat_handler = NULL;                    //stat
zif_handler ori_symlink_handler = NULL;                 //symlink
zif_handler ori_tempnam_handler = NULL;                 //tempnam
zif_handler ori_touch_handler = NULL;                   //touch
zif_handler ori_unlink_handler = NULL;                  //unlink
zif_handler ori_scandir_handler = NULL;                 //scandir
zif_handler ori_header_handler = NULL;                  //header

//added during evaluation with http://testsuite benchmark
zif_handler ori_clearstatcache_handler = NULL;         //payload in 2nd arg
zif_handler ori_disk_free_space_handler = NULL;         //payload in 1st arg
zif_handler ori_disk_total_space_handler = NULL;        //payload in 1st arg    
// zif_handler ori_file_exists_handler = NULL;          //payload in 1st arg
zif_handler ori_fileatime_handler = NULL;        //payload in 1st arg
zif_handler ori_filectime_handler = NULL;        //payload in 1st arg
zif_handler ori_filegroup_handler = NULL;       //payload in 1st arg
zif_handler ori_fileinode_handler = NULL;       //payload in 1st arg
zif_handler ori_filemtime_handler = NULL;       //payload in 1st arg
zif_handler ori_fileowner_handler = NULL;       //payload in 1st arg
zif_handler ori_fileperms_handler = NULL;       //payload in 1st arg
zif_handler ori_filesize_handler = NULL;        //payload in 1st arg
zif_handler ori_filetype_handler = NULL;        //payload in 1st arg
zif_handler ori_lchgroup_handler = NULL;        //payload in 1st arg
zif_handler ori_linkinfo_handler = NULL;        //payload in 1st arg   
zif_handler ori_lstat_handler = NULL;            //payload in 1st arg
zif_handler ori_readlink_handler = NULL;        //payload in 1st arg
zif_handler get_dirtravsfunction_handler(char *func_name_str);

zif_handler get_dirtravsfunction_handler(char *func_name_str){
     if(strcmp(func_name_str,"chgrp")==0){
        return ori_chgrp_handler;
    }else if(strcmp(func_name_str,"chown")==0){
        return ori_chown_handler;
    }else if(strcmp(func_name_str,"chmod")==0){       
        return ori_chmod_handler;            
    }else if(strcmp(func_name_str,"copy")==0){
        return ori_copy_handler;
    }else if(strcmp(func_name_str,"delete")==0){
        return ori_delete_handler;
    }else if(strcmp(func_name_str,"dirname")==0){
        return ori_dirname_handler;
    }else if(strcmp(func_name_str,"file")==0){
        return ori_file_handler;
    }else if(strcmp(func_name_str,"file_get_contents")==0){
        return ori_file_get_contents_handler;
    }else if(strcmp(func_name_str,"fopen")==0){
        return ori_fopen_handler;
    }else if(strcmp(func_name_str,"glob")==0){
        return ori_glob_handler;
    }else if(strcmp(func_name_str,"lchgrp")==0){
        return ori_lchgrp_handler;
    }else if(strcmp(func_name_str,"lchown")==0){
        return ori_lchown_handler;
    }else if(strcmp(func_name_str,"link")==0){
        return ori_link_handler;
    }else if(strcmp(func_name_str,"mkdir")==0){
        return ori_mkdir_handler;
    }else if(strcmp(func_name_str,"move_uploaded_file")==0){
        return ori_move_uploaded_file_handler;
    }else if(strcmp(func_name_str,"parse_ini_file")==0){
        return ori_parse_ini_file_handler;
    }else if(strcmp(func_name_str,"parse_ini_string")==0){
        return ori_parse_ini_string_handler;
    }else if(strcmp(func_name_str,"pathinfo")==0){
        return ori_pathinfo_handler;
    }else if(strcmp(func_name_str,"readfile")==0){
        return ori_readfile_handler;
    }else if(strcmp(func_name_str,"rename")==0){
        return ori_rename_handler;
    }else if(strcmp(func_name_str,"rmdir")==0){
        return ori_rmdir_handler;
    }else if(strcmp(func_name_str,"stat")==0){
        return ori_stat_handler;
    }else if(strcmp(func_name_str,"symlink")==0){
        return ori_symlink_handler;
    }else if(strcmp(func_name_str,"tempnam")==0){
        return ori_tempnam_handler;
    }else if(strcmp(func_name_str,"touch")==0){
        return ori_touch_handler;        
    }else if(strcmp(func_name_str,"unlink")==0){
        return ori_unlink_handler;   
    }else if(strcmp(func_name_str,"scandir")==0){
        return ori_scandir_handler;
    }else if(strcmp(func_name_str,"header")==0){
        return ori_header_handler;
    }else if(strcmp(func_name_str,"clearstatcache")==0){
        return ori_clearstatcache_handler;
    }else if(strcmp(func_name_str,"disk_free_space")==0){
        return ori_disk_free_space_handler;
    }else if(strcmp(func_name_str,"fileatime")==0){
        return ori_fileatime_handler;
    }else if(strcmp(func_name_str,"file_get_contents")==0){
        return ori_file_get_contents_handler;
    }else if(strcmp(func_name_str,"filectime")==0){
        return ori_filectime_handler;
    }else if(strcmp(func_name_str,"filegroup")==0){
        return ori_filegroup_handler;
    }else if(strcmp(func_name_str,"fileinode")==0){
        return ori_fileinode_handler;
    }else if(strcmp(func_name_str,"filemtime")==0){
        return ori_filemtime_handler;
    }else if(strcmp(func_name_str,"fileowner")==0){
        return ori_fileowner_handler;
    }else if(strcmp(func_name_str,"fileperms")==0){
        return ori_fileperms_handler;
    }else if(strcmp(func_name_str,"filesize")==0){
        return ori_filesize_handler;
    }else if(strcmp(func_name_str,"filetype")==0){
        return ori_filetype_handler;
    }else if(strcmp(func_name_str,"lchgroup")==0){
        return ori_lchgroup_handler;
    }else if(strcmp(func_name_str,"linkinfo")==0){
        return ori_linkinfo_handler;
    }else if(strcmp(func_name_str,"lstat")==0){
        return ori_lstat_handler;
    }else if(strcmp(func_name_str,"readlink")==0){
        return ori_readlink_handler;
    }
}

void generic_dirtravs_handler(zend_execute_data *execute_data, zval *return_value){
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
    }else{
        // php_printf("%s\n", func_name);
        func_name_str = func_name;
    }


    if (ZIMPAF_G(coverage_id) == NULL) {
        zif_handler dirtravsfunction_handler = get_dirtravsfunction_handler(func_name_str);
        if(class_method != NULL){
            efree(class_method);
            class_method = NULL;
        }
        func_name_str = NULL;
        dirtravsfunction_handler(INTERNAL_FUNCTION_PARAM_PASSTHRU);
        return;
    }

    const char *filename = "Unknown";
    int lineno = 0;

    zend_execute_data *caller = execute_data->prev_execute_data; // Use execute_data
    if (caller && caller->func && caller->func->op_array.filename) {
        filename = ZSTR_VAL(caller->func->op_array.filename);
        lineno = caller->opline ? caller->opline->lineno : 0;
    }

    cJSON *func_call;
    unsigned int path_opline_type;

    if(strstr(filename,"/var/www/html/")){
        func_call = cJSON_CreateObject();
        cJSON_AddStringToObject(func_call, "function_name", func_name_str);

        zval *path = NULL;
        if(strcmp(func_name,"clearstatcache")==0){
            path= ZEND_CALL_ARG(execute_data, 2);
        }else{
            path= ZEND_CALL_ARG(execute_data, 1);
        }

        if (Z_TYPE_P(path) == IS_STRING) {
            path_opline_type = is_func_param_string_literal(path, execute_data);
            size_t path_len = Z_STRLEN_P(path);
            const char *path_str = Z_STRVAL_P(path);

            //This is to handle header(path) like "Location: /example"
            // Only copy a substring if we want the value after "Location:"
            if (path_len >= 8 && strncasecmp(path_str, "Location", 8) == 0) {
                char *value_copy = NULL;

                const char *colon = strchr(path_str, ':');
                if (colon != NULL) {
                    const char *raw_value = colon + 1;

                    size_t raw_len = path_str + path_len - raw_value;
                    value_copy = estrndup(raw_value, raw_len);  // Allocate safe copy
                    cJSON_AddStringToObject(func_call, "path", value_copy);
                    efree(value_copy);
                } else {
                    // No colon, copy whole string
                    value_copy = estrndup(path_str, path_len);
                    cJSON_AddStringToObject(func_call, "path", value_copy);
                    efree(value_copy);
                }
            } else {
                //Not a header(Location: path) function, copy whole string
                cJSON_AddStringToObject(func_call, "path", path_str);
            }
        } else {
            // Convert non-string to string safely
            zend_string *zs = zval_get_string(path);
            cJSON_AddStringToObject(func_call, "path", ZSTR_VAL(zs));
            zend_string_release(zs);
        }

        cJSON_AddNumberToObject(func_call, "sink_opline_type", path_opline_type);
        if(strcmp(func_name,"copy")==0 || strcmp(func_name,"link")==0 || strcmp(func_name,"move_uploaded_file")==0
            || strcmp(func_name,"rename")==0 || strcmp(func_name,"symlink")==0 ){
            cJSON_AddStringToObject(func_call, "destination", Z_STRVAL_P(ZEND_CALL_ARG(execute_data, 2)));
            zval *destination = ZEND_CALL_ARG(execute_data, 2);
            unsigned int dest_opline_type = is_func_param_string_literal(destination, execute_data);
            cJSON_AddNumberToObject(func_call, "destination_opline_type", dest_opline_type);
        }
        cJSON_AddStringToObject(func_call, "filename", filename);
        cJSON_AddNumberToObject(func_call, "lineno", lineno);
        cJSON_AddItemToArray(ZIMPAF_G(func_call_seq), func_call); 
    }
  
    // Call the original function 
    zif_handler original_handler = get_dirtravsfunction_handler(func_name_str);
    original_handler(INTERNAL_FUNCTION_PARAM_PASSTHRU);
        
    if(strstr(filename,"/var/www/html/")){
        char *retval_str = get_return_value_string(return_value);
        cJSON_AddStringToObject(func_call, "return_value", retval_str);
        efree(retval_str);
    }

    if(class_method != NULL){
        efree(class_method);
        class_method = NULL;
    }
    func_name_str = NULL;
}

void hook_chgrp(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_chgrp_func = zend_hash_str_find_ptr(CG(function_table), "chgrp", sizeof("chgrp")-1);
    if (ori_chgrp_func && ori_chgrp_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_chgrp_handler = ori_chgrp_func->internal_function.handler;
        ori_chgrp_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_chown(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_chown_func = zend_hash_str_find_ptr(CG(function_table), "chown", sizeof("chown")-1);
    if (ori_chown_func && ori_chown_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_chown_handler = ori_chown_func->internal_function.handler;
        ori_chown_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_chmod(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_chmod_func = zend_hash_str_find_ptr(CG(function_table), "chmod", sizeof("chmod")-1);
    if (ori_chmod_func && ori_chmod_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_chmod_handler = ori_chmod_func->internal_function.handler;
        ori_chmod_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_copy(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_copy_func = zend_hash_str_find_ptr(CG(function_table), "copy", sizeof("copy")-1);
    if (ori_copy_func && ori_copy_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_copy_handler = ori_copy_func->internal_function.handler;
        ori_copy_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_delete(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_delete_func = zend_hash_str_find_ptr(CG(function_table), "delete", sizeof("delete")-1);
    if (ori_delete_func && ori_delete_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_delete_handler = ori_delete_func->internal_function.handler;
        ori_delete_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_dirname(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_dirname_func = zend_hash_str_find_ptr(CG(function_table), "dirname", sizeof("dirname")-1);
    if (ori_dirname_func && ori_dirname_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_dirname_handler = ori_dirname_func->internal_function.handler;
        ori_dirname_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_file(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_file_func = zend_hash_str_find_ptr(CG(function_table), "file", sizeof("file")-1);
    if (ori_file_func && ori_file_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_file_handler = ori_file_func->internal_function.handler;
        ori_file_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_file_get_contents(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_file_get_contents_func = zend_hash_str_find_ptr(CG(function_table), "file_get_contents", sizeof("file_get_contents")-1);
    if (ori_file_get_contents_func && ori_file_get_contents_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_file_get_contents_handler = ori_file_get_contents_func->internal_function.handler;
        ori_file_get_contents_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_fopen(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_fopen_func = zend_hash_str_find_ptr(CG(function_table), "fopen", sizeof("fopen")-1);
    if (ori_fopen_func && ori_fopen_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_fopen_handler = ori_fopen_func->internal_function.handler;
        ori_fopen_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_glob(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_glob_func = zend_hash_str_find_ptr(CG(function_table), "glob", sizeof("glob")-1);
    if (ori_glob_func && ori_glob_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_glob_handler = ori_glob_func->internal_function.handler;
        ori_glob_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_lchgrp(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_lchgrp_func = zend_hash_str_find_ptr(CG(function_table), "lchgrp", sizeof("lchgrp")-1);
    if (ori_lchgrp_func && ori_lchgrp_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_lchgrp_handler = ori_lchgrp_func->internal_function.handler;
        ori_lchgrp_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_lchown(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_lchown_func = zend_hash_str_find_ptr(CG(function_table), "lchown", sizeof("lchown")-1);
    if (ori_lchown_func && ori_lchown_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_lchown_handler = ori_lchown_func->internal_function.handler;
        ori_lchown_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_link(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_link_func = zend_hash_str_find_ptr(CG(function_table), "link", sizeof("link")-1);
    if (ori_link_func && ori_link_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_link_handler = ori_link_func->internal_function.handler;
        ori_link_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_mkdir(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_mkdir_func = zend_hash_str_find_ptr(CG(function_table), "mkdir", sizeof("mkdir")-1);
    if (ori_mkdir_func && ori_mkdir_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_mkdir_handler = ori_mkdir_func->internal_function.handler;
        ori_mkdir_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_move_uploaded_file(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_move_uploaded_file_func = zend_hash_str_find_ptr(CG(function_table), "move_uploaded_file", sizeof("move_uploaded_file")-1);
    if (ori_move_uploaded_file_func && ori_move_uploaded_file_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_move_uploaded_file_handler = ori_move_uploaded_file_func->internal_function.handler;
        ori_move_uploaded_file_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_parse_ini_file(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_parse_ini_file_func = zend_hash_str_find_ptr(CG(function_table), "parse_ini_file", sizeof("parse_ini_file")-1);
    if (ori_parse_ini_file_func && ori_parse_ini_file_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_parse_ini_file_handler = ori_parse_ini_file_func->internal_function.handler;
        ori_parse_ini_file_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_parse_ini_string(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_parse_ini_string_func = zend_hash_str_find_ptr(CG(function_table), "parse_ini_string", sizeof("parse_ini_string")-1);
    if (ori_parse_ini_string_func && ori_parse_ini_string_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_parse_ini_string_handler = ori_parse_ini_string_func->internal_function.handler;
        ori_parse_ini_string_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_pathinfo(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_pathinfo_func = zend_hash_str_find_ptr(CG(function_table), "pathinfo", sizeof("pathinfo")-1);
    if (ori_pathinfo_func && ori_pathinfo_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_pathinfo_handler = ori_pathinfo_func->internal_function.handler;
        ori_pathinfo_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_readfile(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_readfile_func = zend_hash_str_find_ptr(CG(function_table), "readfile", sizeof("readfile")-1);
    if (ori_readfile_func && ori_readfile_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_readfile_handler = ori_readfile_func->internal_function.handler;
        ori_readfile_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_rename(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_rename_func = zend_hash_str_find_ptr(CG(function_table), "rename", sizeof("rename")-1);
    if (ori_rename_func && ori_rename_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_rename_handler = ori_rename_func->internal_function.handler;
        ori_rename_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_rmdir(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_rmdir_func = zend_hash_str_find_ptr(CG(function_table), "rmdir", sizeof("rmdir")-1);
    if (ori_rmdir_func && ori_rmdir_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_rmdir_handler = ori_rmdir_func->internal_function.handler;
        ori_rmdir_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_stat(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_stat_func = zend_hash_str_find_ptr(CG(function_table), "stat", sizeof("stat")-1);
    if (ori_stat_func && ori_stat_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_stat_handler = ori_stat_func->internal_function.handler;
        ori_stat_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_symlink(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_symlink_func = zend_hash_str_find_ptr(CG(function_table), "symlink", sizeof("symlink")-1);
    if (ori_symlink_func && ori_symlink_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_symlink_handler = ori_symlink_func->internal_function.handler;
        ori_symlink_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_tempnam(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_tempnam_func = zend_hash_str_find_ptr(CG(function_table), "tempnam", sizeof("tempnam")-1);
    if (ori_tempnam_func && ori_tempnam_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_tempnam_handler = ori_tempnam_func->internal_function.handler;
        ori_tempnam_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_touch(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_touch_func = zend_hash_str_find_ptr(CG(function_table), "touch", sizeof("touch")-1);
    if (ori_touch_func && ori_touch_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_touch_handler = ori_touch_func->internal_function.handler;
        ori_touch_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_unlink(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_unlink_func = zend_hash_str_find_ptr(CG(function_table), "unlink", sizeof("unlink")-1);
    if (ori_unlink_func && ori_unlink_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_unlink_handler = ori_unlink_func->internal_function.handler;
        ori_unlink_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_scandir(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_scandir_func = zend_hash_str_find_ptr(CG(function_table), "scandir", sizeof("scandir")-1);
    if (ori_scandir_func && ori_scandir_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_scandir_handler = ori_scandir_func->internal_function.handler;
        ori_scandir_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_header(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_header_func = zend_hash_str_find_ptr(CG(function_table), "header", sizeof("header")-1);
    if (ori_header_func && ori_header_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_header_handler = ori_header_func->internal_function.handler;
        ori_header_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}

void hook_clearstatcache(){//payload in 2nd arg 
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_clearstatcache_func = zend_hash_str_find_ptr(CG(function_table), "clearstatcache", sizeof("clearstatcache")-1);
    if (ori_clearstatcache_func && ori_clearstatcache_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_clearstatcache_handler = ori_clearstatcache_func->internal_function.handler;
        ori_clearstatcache_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}          
void hook_disk_free_space() {                      //payload in 1st arg
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_disk_free_space_func = zend_hash_str_find_ptr(CG(function_table), "disk_free_space", sizeof("disk_free_space")-1);
    if (ori_disk_free_space_func && ori_disk_free_space_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_disk_free_space_handler = ori_disk_free_space_func->internal_function.handler;
        ori_disk_free_space_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}  

void hook_disk_total_space(){               //payload in 1st arg
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_disk_total_space_func = zend_hash_str_find_ptr(CG(function_table), "disk_total_space", sizeof("disk_total_space")-1);
    if (ori_disk_total_space_func && ori_disk_total_space_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_disk_total_space_handler = ori_disk_total_space_func->internal_function.handler;
        ori_disk_total_space_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}                 

void hook_fileatime(){          //payload in 1st arg
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_fileatime_func = zend_hash_str_find_ptr(CG(function_table), "fileatime", sizeof("fileatime")-1);
    if (ori_fileatime_func && ori_fileatime_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_fileatime_handler = ori_fileatime_func->internal_function.handler;
        ori_fileatime_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }   
}        

void hook_filectime(){          //payload in 1st arg
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_filectime_func = zend_hash_str_find_ptr(CG(function_table), "filectime", sizeof("filectime")-1);
    if (ori_filectime_func && ori_filectime_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_filectime_handler = ori_filectime_func->internal_function.handler;
        ori_filectime_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}        

void hook_filegroup(){          //payload in 1st arg
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_filegroup_func = zend_hash_str_find_ptr(CG(function_table), "filegroup", sizeof("filegroup")-1);
    if (ori_filegroup_func && ori_filegroup_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_filegroup_handler = ori_filegroup_func->internal_function.handler;
        ori_filegroup_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}      

void hook_fileinode(){      //payload in 1st arg
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_fileinode_func = zend_hash_str_find_ptr(CG(function_table), "fileinode", sizeof("fileinode")-1);
    if (ori_fileinode_func && ori_fileinode_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_fileinode_handler = ori_fileinode_func->internal_function.handler;
        ori_fileinode_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}       

void hook_filemtime(){      //payload in 1st arg
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_filemtime_func = zend_hash_str_find_ptr(CG(function_table), "filemtime", sizeof("filemtime")-1);
    if (ori_filemtime_func && ori_filemtime_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_filemtime_handler = ori_filemtime_func->internal_function.handler;
        ori_filemtime_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}       

void hook_fileowner(){      //payload in 1st arg
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_fileowner_func = zend_hash_str_find_ptr(CG(function_table), "fileowner", sizeof("fileowner")-1);
    if (ori_fileowner_func && ori_fileowner_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_fileowner_handler = ori_fileowner_func->internal_function.handler;
        ori_fileowner_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}       

void hook_fileperms(){          //payload in 1st arg
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_fileperms_func = zend_hash_str_find_ptr(CG(function_table), "fileperms", sizeof("fileperms")-1);
    if (ori_fileperms_func && ori_fileperms_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_fileperms_handler = ori_fileperms_func->internal_function.handler;
        ori_fileperms_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}       

void hook_filesize(){           //payload in 1st arg
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_filesize_func = zend_hash_str_find_ptr(CG(function_table), "filesize", sizeof("filesize")-1);
    if (ori_filesize_func && ori_filesize_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_filesize_handler = ori_filesize_func->internal_function.handler;
        ori_filesize_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}        

void hook_filetype(){           //payload in 1st arg
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_filetype_func = zend_hash_str_find_ptr(CG(function_table), "filetype", sizeof("filetype")-1);
    if (ori_filetype_func && ori_filetype_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_filetype_handler = ori_filetype_func->internal_function.handler;
        ori_filetype_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}        

void hook_lchgroup(){           //payload in 1st arg
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_lchgroup_func = zend_hash_str_find_ptr(CG(function_table), "lchgrp", sizeof("lchgrp")-1);
    if (ori_lchgroup_func && ori_lchgroup_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_lchgroup_handler = ori_lchgroup_func->internal_function.handler;
        ori_lchgroup_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}      

void hook_linkinfo(){           //payload in 1st arg
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_linkinfo_func = zend_hash_str_find_ptr(CG(function_table), "linkinfo", sizeof("linkinfo")-1);
    if (ori_linkinfo_func && ori_linkinfo_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_linkinfo_handler = ori_linkinfo_func->internal_function.handler;
        ori_linkinfo_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}     

void hook_lstat(){              //payload in 1st arg    
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_lstat_func = zend_hash_str_find_ptr(CG(function_table), "lstat", sizeof("lstat")-1);
    if (ori_lstat_func && ori_lstat_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_lstat_handler = ori_lstat_func->internal_function.handler;
        ori_lstat_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}            

void hook_readlink(){           //payload in 1st arg
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_readlink_func = zend_hash_str_find_ptr(CG(function_table), "readlink", sizeof("readlink")-1);
    if (ori_readlink_func && ori_readlink_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_readlink_handler = ori_readlink_func->internal_function.handler;
        ori_readlink_func->internal_function.handler = (zif_handler)generic_dirtravs_handler;
    }
}        

