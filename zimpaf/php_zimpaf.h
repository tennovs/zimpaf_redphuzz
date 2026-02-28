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

/* zimpaf extension for PHP */
#ifndef PHP_ZIMPAF_H
# define PHP_ZIMPAF_H

#include "libcjson/cJSON.h"

extern zend_module_entry zimpaf_module_entry;
# define phpext_zimpaf_ptr &zimpaf_module_entry

#if defined(ZTS) && defined(COMPILE_DL_ZIMPAF)
    ZEND_TSRMLS_CACHE_EXTERN()
#endif


#define PHP_ZIMPAF_VERSION "1.0.0"
#define MAX_CHARS 1000			//max chars in file path and branch instruction executed for each request path
#define MAX_FILES 50			//max #files involved in each request execution, this is number of rows in path table
#define LENGTH_FNAME 255		//max length of filename containing branch instruction executed for each request path

ZEND_BEGIN_MODULE_GLOBALS(zimpaf)
    char *coverage_id;
    cJSON *func_call_seq;
    cJSON *input_comparisons;

    char **path_table;
    unsigned int path_table_size;	//size of path table, number of rows in path table  
    int pt_cur_rows;                //current row in path table, used to store the current file name and branch instruction executed
    unsigned int pt_cur_rows_size;  //the size of current row in path table, initially set to MAX_CHARS
    char *cur_filename;	            //file name currently active/being executed in the path table
    //these are vars to handle looping and multiple hooked opcodes which refer to the same line 
    //and path condition in conditional_statement_handler(...) in zimpaf.c or the main module
    int prev_lineno;			//previous line number of the file being executed
    int prev_path_condition;	//previous path condition of the file being executed
    //this is to avoid logging the same language construct call, the same: funcname and parameter are the same
    zend_ulong last_hash;       //last hash of INCLUDE_OR_EVAL and EXIT in generic_lang_construct_handler(...)
    //this is to avoid logging the same input parameter comparisons
    zend_ulong last_input_cmp_hash; //used in log_request_param_comparison(...) in utils.c

    //These purpose is to avoid duplication of exception report caught by zimpaf_observe_exception, 
    //zimpaf_observer_error_handler, and zend_error_cb, see libhooks/error_exception_hook.c
    zend_object *last_observed_ex; //to hold zend last exception object logged
    unsigned int error_exception_just_logged;
    char *error_exception_just_logged_filename;
    int error_exception_just_logged_lineno;
    char *error_exception_just_logged_funcname; //error does not hav

    zend_string *last_error_msg; //for identifying whether the ZEND_INCLUDE_OR_EVAL success or not. treated as return value

    //for time measurement of request processing in RINIT and RSHUTDOWN
    double start_time;
    double end_time;

    JMP_BUF bailout_buf;        //global vars for bailout as indicator for script early termination
    JMP_BUF *orig_bailout;      //implemented in rinit and rshutdown, used to restore the original bailout buffer      
    int bailout_triggered;
ZEND_END_MODULE_GLOBALS(zimpaf)

/* Declaration of visibility (SAFE TO INCLUDE EVERYWHERE) */
ZEND_EXTERN_MODULE_GLOBALS(zimpaf)
#ifdef ZTS
    #include "TSRM.h"
    #define ZIMPAF_G(v) TSRMG(zimpaf_globals_id, zend_zimpaf_globals *, v)
#else
    #define ZIMPAF_G(v) (zimpaf_globals.v)
#endif /* ZTS */
#endif	/* PHP_ZIMPAF_H */
// #ifdef ZTS
//     #define ZIMPAF_G(v) TSRMG(zimpaf_globals_id, zend_zimpaf_globals *, v)
// #else
//     /* 1. Define the accessor using the PHP 8 internal logic */
//     #ifdef ZEND_MODULE_GLOBALS_ACCESSOR
//         #define ZIMPAF_G(v) ZEND_MODULE_GLOBALS_ACCESSOR(zimpaf, v)
//     #else
//         #define ZIMPAF_G(v) (zimpaf_globals.v)
//     #endif
// #endif
// #endif	/* PHP_ZIMPAF_H */

