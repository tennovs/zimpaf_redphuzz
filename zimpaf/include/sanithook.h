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


#ifndef SANITATION_HOOK_H
#define SANITATION_HOOK_H

#include "php.h"

void hook_htmlspecialchars();
void hook_htmlentities();
void hook_addslashes();
void hook_stripslashes();
void hook_strip_tags();
void hook_mysqli_real_escape_string(); 
void hook_mysqli_real_escape_string_cm();
void hook_pdo_quote_cm();
void hook_preg_replace();
void hook_preg_match();
void hook_realpath();
void hook_basename();
void hook_escapeshellarg();
void hook_escapeshellcmd();
void hook_str_replace();
void hook_strpos();
void hook_stripos();
void hook_filter_var();
void hook_filter_var_array();
void hook_filter_input();
void hook_filter_input_array();
void hook_libxml_disable_entity_loader();
void hook_is_numeric();
void hook_base64_decode();
void hook_json_decode();
void hook_fnmatch();
void hook_is_file();

//added during evaluation with http://testsuite benchmark
void hook_file_exists();          //payload in 1st arg
void hook_is_dir();         //payload in 1st arg
void hook_is_executable();  //payload in 1st arg    
void hook_is_link();        //payload in 1st arg
void hook_is_readable();    //payload in 1st arg
void hook_is_writable();    //payload in 1st arg
void hook_is_uploaded_file(); //payload in 1st arg

#endif


