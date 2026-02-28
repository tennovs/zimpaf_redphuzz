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


#ifndef XXE_HOOK_H
#define XXE_HOOK_H

#include "php.h"

void hook_simplexml_load_string();                   //payload in 1st arg, vulnerable in PHP < 8
void hook_simplexml_load_file();                     //payload in 1st arg, vulnerable in PHP < 8    
void hook_domdocument_load_cm();                     //payload in 1st arg               
void hook_domdocument_loadxml_cm();                  //payload in 1st arg
void hook_xmlreader_xml_cm();                        //payload in 1st arg
void hook_xmlreader_open_cm();                       //payload in 1st arg
void hook_xmlreader_read_cm();   
void hook_xml_set_external_entity_ref_handler();     //internal to userland php, set handler for xml entity
void hook_xml_parse();                               //xml_parse function, vulnerable in PHP < 8

#endif
