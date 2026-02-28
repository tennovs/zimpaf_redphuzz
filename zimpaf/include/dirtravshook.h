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


#ifndef DIRTRAVS_HOOK_H
#define DIRTRAVS_HOOK_H

#include "php.h"

void hook_chgrp();                  //payload in 1st arg
void hook_chown();                  //payload in 1st arg
void hook_chmod();                  //payload in 1st arg
void hook_copy();                   //payload in 1st and 2nd arg
void hook_delete();                 //payload in 1st arg
void hook_dirname();                //payload in 1st arg
void hook_file();                   //payload in 1st arg
void hook_file_get_contents();      //payload in 1st arg
void hook_fopen();                  //payload in 1st arg
void hook_glob();                   //payload in 1st arg
void hook_lchgrp();                 //payload in 1st arg  
void hook_lchown();                 //payload in 1st arg
void hook_link();                   //payload in 1st and 2nd arg
void hook_mkdir();                  //payload in 1st arg
void hook_move_uploaded_file();     //payload in 1st and 2nd arg
void hook_parse_ini_file();         //payload in 1st arg
void hook_parse_ini_string();       //payload in 1st arg
void hook_pathinfo();               //payload in 1st arg
void hook_readfile();               //payload in 1st arg
void hook_rename();                 //payload in 1st and 2nd arg
void hook_rmdir();                  //payload in 1st arg
void hook_stat();                   //payload in 1st arg
void hook_symlink();                //payload in 1st and 2nd arg       
void hook_tempnam();                //payload in 1st arg
void hook_touch();                  //payload in 1st arg
void hook_unlink();                 //payload in 1st arg
void hook_scandir();                //payload in 1st arg
void hook_header();                 //payload in 1st arg

//added during evaluation with http://testsuite benchmark
void hook_clearstatcache();         //payload in 2nd arg  
void hook_disk_free_space();         //payload in 1st arg
void hook_disk_total_space();        //payload in 1st arg    
void hook_file_get_contents();   //payload in 1st arg
void hook_fileatime();        //payload in 1st arg
void hook_filectime();        //payload in 1st arg
void hook_filegroup();       //payload in 1st arg
void hook_fileinode();       //payload in 1st arg
void hook_filemtime();       //payload in 1st arg
void hook_fileowner();       //payload in 1st arg
void hook_fileperms();       //payload in 1st arg
void hook_filesize();        //payload in 1st arg
void hook_filetype();        //payload in 1st arg
void hook_lchgroup();      //payload in 1st arg
void hook_linkinfo();     //payload in 1st arg
void hook_lstat();            //payload in 1st arg
void hook_readlink();        //payload in 1st arg

#endif

