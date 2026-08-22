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


#include "../include/hook_installer.h"
#include "../include/codexechook.h"
#include "../include/dbhook.h"
#include "../include/deserhook.h"
#include "../include/dirtravshook.h"
#include "../include/error_exception_hook.h"
#include "../include/sanithook.h"
#include "../include/xxehook.h"
#include "../include/flowfunchook.h"
#include "zend_observer.h"



void install_hooks(){
	//hook for code execution
	hook_assert();
	hook_system();
	hook_exec();
	hook_passthru();
	hook_shell_exec();
	hook_popen();
	hook_proc_open();								//7 functions

	//dbhook for sqli vuln probe
	hook_mysqli_query();
	hook_mysqli_query_cm();
	hook_pdo_query_cm();
	hook_mysqli_real_query();
	hook_mysqli_real_query_cm();
	hook_mysqli_multi_query();
	hook_mysqli_multi_query_cm();

	hook_mysqli_prepare();
	hook_mysqli_prepare_cm();
	hook_pdo_prepare_cm();
	hook_pdo_exec_cm();
	
	hook_mysqli_stmt_bind_param();
	hook_mysqli_stmt_bind_param_cm();
	hook_pdostmt_bindParam_cm();
	hook_pdostmt_bindValue_cm();

	hook_mysqli_execute_query();
	hook_mysqli_execute_query_cm();
	hook_mysqli_stmt_execute();
	hook_mysqli_stmt_execute_cm();
	hook_pdostmt_execute_cm();						//20 functions

	//hook for deserialization vuln probe
	hook_unserialize();
	hook_yaml_parse();
	hook_yaml_parse_file();
	hook_unpack();
	hook_igbinary_unserialize();					//5 functions

	// hook for dirtravshook 
	hook_chgrp();
	hook_chown();
	hook_chmod();
	hook_copy();
	hook_delete();
	hook_dirname();
	hook_file();	
	hook_file_get_contents();
	hook_fopen();
	hook_glob();
	hook_lchgrp();
	hook_lchown();
	hook_link();
	hook_mkdir();
	hook_move_uploaded_file();	
	hook_parse_ini_file();
	hook_parse_ini_string();
	hook_pathinfo();
	hook_readfile();
	hook_rename();
	hook_rmdir();
	hook_stat();
	hook_symlink();
	hook_tempnam();
	hook_touch();
	hook_unlink();
	hook_scandir();									
	hook_header();//28 functions

	//added during evaluation with http://testsuite benchmark
 	hook_clearstatcache();         	//payload in 2nd arg  
	hook_disk_free_space();         //payload in 1st arg
	hook_disk_total_space();        //payload in 1st arg    
	hook_fileatime();        		//payload in 1st arg
 	hook_filectime();        		//payload in 1st arg
	hook_filegroup();       		//payload in 1st arg
	hook_fileinode();       		//payload in 1st arg
	hook_filemtime();       		//payload in 1st arg
	hook_fileowner();       		//payload in 1st arg
	hook_fileperms();       		//payload in 1st arg
	hook_filesize();        		//payload in 1st arg
	hook_filetype();        		//payload in 1st arg
	hook_lchgroup();      			//payload in 1st arg
	hook_linkinfo();     			//payload in 1st arg
	hook_lstat();           		//payload in 1st arg
	hook_readlink();        		//payload in 1st arg

	//hook for sanitations
	hook_htmlspecialchars();
	hook_htmlentities();
	hook_addslashes();
	hook_stripslashes();
	hook_strip_tags();
	hook_mysqli_real_escape_string();
	hook_mysqli_real_escape_string_cm();
	hook_pdo_quote_cm();
	hook_preg_replace();
	hook_preg_match();
	hook_realpath();
	hook_basename();
	hook_escapeshellarg();
	hook_escapeshellcmd();
	hook_str_replace();
	hook_strpos();
	hook_stripos();
	hook_filter_var();
	hook_filter_var_array();
	hook_filter_input();
	hook_filter_input_array();
	hook_libxml_disable_entity_loader();
	hook_is_numeric();
	hook_base64_decode();
	hook_json_decode();
	hook_fnmatch();									
	hook_is_file();				//27 functions

	//added during evaluation with http://testsuite benchmark
	hook_file_exists();         //payload in 1st arg
	hook_is_dir();         		//payload in 1st arg
	hook_is_executable();  		//payload in 1st arg    
	hook_is_link();        		//payload in 1st arg
	hook_is_readable();    		//payload in 1st arg
	hook_is_writable();    		//payload in 1st arg
	hook_is_uploaded_file(); 	//payload in 1st arg


	//hook for XXE
	hook_simplexml_load_string();
	hook_simplexml_load_file();
	hook_domdocument_load_cm();
	hook_domdocument_loadxml_cm();
	hook_xmlreader_xml_cm();
	hook_xmlreader_open_cm();
	hook_xmlreader_read_cm();
	hook_xml_set_external_entity_ref_handler();
	hook_xml_parse();								//9 functions

	//hook for generic error and exception, the main mechanism for error and exception reporting in zend interpreter
	hook_zend_error_cb();
	hook_zend_throw_exception_hook();
	/*for cold error, means errors that are raised to userland via different path than main main mechanism above
	 *zimpaf_observer_error_handler is the error handler function defined in error_exception_hook.c.
	 *Just in case Php version < 8 does not have this mechanism.
	*/
	zend_observer_error_register(zimpaf_observer_error_handler);

	//hook for flow function, e.g exit()
	/*Exit hook for PHP version >=8.4*/
	#if PHP_VERSION_ID >= 80400
		hook_exit();
	#endif

	//hook performance related functions, e.g. malloc(), free(), realloc()
	// hook_memory_management();
}

void uninstall_hooks(){
    #if defined(ZTS) && defined(COMPILE_DL_ZIMPAF)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif

    //unhook for code execution
}