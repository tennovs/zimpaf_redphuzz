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



#include "../include/xxehook.h"
#include "../include/utils.h"
#include "../php_zimpaf.h"

zif_handler ori_simplexml_load_string_handler = NULL;                       //simplexml_load_string
zif_handler ori_simplexml_load_file_handler = NULL;                         //simplexml_load_file
zif_handler ori_domdocument_load_method_handler = NULL;                     //domdocument::load
zif_handler ori_domdocument_loadxml_method_handler = NULL;                 //domdocument::loadxml
zif_handler ori_xmlreader_xml_method_handler = NULL;                        //xmlreader::xml
zif_handler ori_xmlreader_open_method_handler = NULL;                       //xmlreader::open
zif_handler ori_xmlreader_read_method_handler = NULL;                       //xmlreader::read
zif_handler ori_xml_set_external_entity_ref_handler_handler = NULL;         //xml_set_external_entity_ref_handler
zif_handler ori_xml_parse_handler = NULL;                                   //xml_parse
zif_handler get_xxefunction_handler(char *scope_name, char *func_name);

zif_handler get_xxefunction_handler(char *scope_name, char *func_name){
    if(scope_name, func_name){
        if(strcmp(scope_name,"DOMDocument")== 0){
            if(strcmp(func_name,"load")==0){
                return ori_domdocument_load_method_handler; 
            }else if(strcmp(func_name,"loadXML")==0){
                return ori_domdocument_loadxml_method_handler; 
            }
        }else if(strcmp(scope_name,"XMLReader")== 0){
            if(strcmp(func_name,"XML")==0){
                return ori_xmlreader_xml_method_handler; 
            }else if(strcmp(func_name,"open")==0){
                return ori_xmlreader_open_method_handler; 
            }else if(strcmp(func_name,"read")==0){
                return ori_xmlreader_read_method_handler; 
            }
        }
    }else if(strcmp(func_name,"simplexml_load_string")==0){
        return ori_simplexml_load_string_handler; 
    }else if(strcmp(func_name,"simplexml_load_file")==0){
        return ori_simplexml_load_file_handler; 
    }else if(strcmp(func_name,"xml_set_external_entity_ref_handler")==0){
        return ori_xml_set_external_entity_ref_handler_handler; 
    }else if(strcmp(func_name,"xml_parse")==0){
        return ori_xml_parse_handler; 
    }

}

void generic_xxe_handler(zend_execute_data *execute_data, zval *return_value){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    char *func_name = ZSTR_VAL(execute_data->func->common.function_name);
    unsigned int num_args = ZEND_CALL_NUM_ARGS(execute_data); // Get number of arguments
    unsigned int i;
    char *func_name_str=NULL, *class_method = NULL;

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
        zif_handler xxefunction_handler = get_xxefunction_handler(scope_name, func_name);
        if(class_method != NULL){
            efree(class_method);
            class_method = NULL;
        }
        func_name_str = NULL;
        xxefunction_handler(INTERNAL_FUNCTION_PARAM_PASSTHRU);
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
    unsigned int payload_opline_type = 0;
    if(strcmp(func_name_str,"xml_set_external_entity_ref_handler") != 0){
        zval *payload = ZEND_CALL_ARG(execute_data, 1);
        payload_opline_type = is_func_param_string_literal(payload, execute_data);
    }

    cJSON *func_call = cJSON_CreateObject();
    cJSON_AddStringToObject(func_call, "function_name", func_name_str);
    cJSON_AddStringToObject(func_call, "xml_payload", strcmp(func_name_str,"xml_set_external_entity_ref_handler") != 0 ? 
                    Z_STRVAL_P(ZEND_CALL_ARG(execute_data, 1)) : "");
    cJSON_AddNumberToObject(func_call, "sink_opline_type", payload_opline_type);
    if (strcmp(func_name_str, "simplexml_load_string") == 0 ||
        strcmp(func_name_str, "simplexml_load_file") == 0 ||
        strcmp(func_name_str, "XMLReader::XML") == 0 ||
        strcmp(func_name_str, "XMLReader::open") == 0){ 
        cJSON_AddNumberToObject(func_call, "options", Z_LVAL_P(ZEND_CALL_ARG(execute_data, 3)));
    }else if(strcmp(func_name_str, "DOMDocument::loadXML") == 0 ||
            strcmp(func_name_str, "DOMDocument::load") == 0) {
        cJSON_AddNumberToObject(func_call, "options", Z_LVAL_P(ZEND_CALL_ARG(execute_data, 2)));
    }
    cJSON_AddStringToObject(func_call, "filename", filename);
    cJSON_AddNumberToObject(func_call, "lineno", lineno);
    cJSON_AddItemToArray(ZIMPAF_G(func_call_seq), func_call);

    //call original handler
    zif_handler xxefunction_handler = get_xxefunction_handler(scope_name, func_name);
    xxefunction_handler(INTERNAL_FUNCTION_PARAM_PASSTHRU);

    if(class_method != NULL){
        efree(class_method);
        class_method = NULL;
    }
    func_name_str = NULL;
    
    char *retval_str = get_return_value_string(return_value);
    cJSON_AddStringToObject(func_call, "return_value", retval_str);
    efree(retval_str);
}

void hook_simplexml_load_string(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_simplexml_load_string_func = zend_hash_str_find_ptr(CG(function_table), "simplexml_load_string", sizeof("simplexml_load_string")-1);
    if (ori_simplexml_load_string_func && ori_simplexml_load_string_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_simplexml_load_string_handler = ori_simplexml_load_string_func->internal_function.handler;
        ori_simplexml_load_string_func->internal_function.handler = (zif_handler)generic_xxe_handler;
    }
}
void hook_simplexml_load_file(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_simplexml_load_file_func = zend_hash_str_find_ptr(CG(function_table), "simplexml_load_file", sizeof("simplexml_load_file")-1);
    if (ori_simplexml_load_file_func && ori_simplexml_load_file_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_simplexml_load_file_handler = ori_simplexml_load_file_func->internal_function.handler;
        ori_simplexml_load_file_func->internal_function.handler = (zif_handler)generic_xxe_handler;
    }
}

//look for LIBXML_NOENT (int) that may lead to XXE
void hook_domdocument_load_cm(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_class_entry *domdocumement_ce = zend_hash_str_find_ptr(CG(class_table), "domdocument", sizeof("domdocument")-1);
    if (domdocumement_ce) {
        zend_function *ori_domdocument_load_func = zend_hash_str_find_ptr(&domdocumement_ce->function_table, "load", sizeof("load")-1);
        if (ori_domdocument_load_func && ori_domdocument_load_func->type == ZEND_INTERNAL_FUNCTION) {
            ori_domdocument_load_method_handler = ori_domdocument_load_func->internal_function.handler;
            ori_domdocument_load_func->internal_function.handler = (zif_handler)generic_xxe_handler;
        }
    }
}

//look for LIBXML_NOENT (int) that may lead to XXE
void hook_domdocument_loadxml_cm(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_class_entry *domdocument_ce = zend_hash_str_find_ptr(CG(class_table), "domdocument", sizeof("domdocument")-1);
    if (domdocument_ce) {
        zend_function *ori_domdocument_loadxml_func = zend_hash_str_find_ptr(&domdocument_ce->function_table, "loadxml", sizeof("loadxml")-1);
        if (ori_domdocument_loadxml_func && ori_domdocument_loadxml_func->type == ZEND_INTERNAL_FUNCTION) {
            ori_domdocument_loadxml_method_handler = ori_domdocument_loadxml_func->internal_function.handler;
            ori_domdocument_loadxml_func->internal_function.handler = (zif_handler)generic_xxe_handler;
        }
    }
}

void hook_xmlreader_xml_cm(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_class_entry *xmlreader_ce = zend_hash_str_find_ptr(CG(class_table), "xmlreader", sizeof("xmlreader")-1);
    if (xmlreader_ce) {
        zend_function *ori_xmlreader_xml_func = zend_hash_str_find_ptr(&xmlreader_ce->function_table, "xml", sizeof("xml")-1);
        if (ori_xmlreader_xml_func && ori_xmlreader_xml_func->type == ZEND_INTERNAL_FUNCTION) {
            ori_xmlreader_xml_method_handler = ori_xmlreader_xml_func->internal_function.handler;
            ori_xmlreader_xml_func->internal_function.handler = (zif_handler)generic_xxe_handler;
        }
    }
}

void hook_xmlreader_open_cm(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_class_entry *xmlreader_ce = zend_hash_str_find_ptr(CG(class_table), "xmlreader", sizeof("xmlreader")-1);
    if (xmlreader_ce) {
        zend_function *ori_xmlreader_open_func = zend_hash_str_find_ptr(&xmlreader_ce->function_table, "open", sizeof("open")-1);
        if (ori_xmlreader_open_func && ori_xmlreader_open_func->type == ZEND_INTERNAL_FUNCTION) {
            ori_xmlreader_open_method_handler = ori_xmlreader_open_func->internal_function.handler;
            ori_xmlreader_open_func->internal_function.handler = (zif_handler)generic_xxe_handler;
        }
    }
}

void hook_xmlreader_read_cm(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_class_entry *xmlreader_ce = zend_hash_str_find_ptr(CG(class_table), "xmlreader", sizeof("xmlreader")-1);
    if (xmlreader_ce) {
        zend_function *ori_xmlreader_read_func = zend_hash_str_find_ptr(&xmlreader_ce->function_table, "read", sizeof("read")-1);
        if (ori_xmlreader_read_func && ori_xmlreader_read_func->type == ZEND_INTERNAL_FUNCTION) {
            ori_xmlreader_read_method_handler = ori_xmlreader_read_func->internal_function.handler;
            ori_xmlreader_read_func->internal_function.handler = (zif_handler)generic_xxe_handler;
            // ori_xmlreader_read_func->internal_function.handler = (zif_handler)xmlreader_read_handler;
        }
    }
}

void hook_xml_set_external_entity_ref_handler(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_xml_set_external_entity_ref_handler_func = zend_hash_str_find_ptr(CG(function_table), "xml_set_external_entity_ref_handler", sizeof("xml_set_external_entity_ref_handler")-1);
    if (ori_xml_set_external_entity_ref_handler_func && ori_xml_set_external_entity_ref_handler_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_xml_set_external_entity_ref_handler_handler = ori_xml_set_external_entity_ref_handler_func->internal_function.handler;
        ori_xml_set_external_entity_ref_handler_func->internal_function.handler = (zif_handler)generic_xxe_handler;
    }
}

void hook_xml_parse(){
    #if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    zend_function *ori_xml_parse_func = zend_hash_str_find_ptr(CG(function_table), "xml_parse", sizeof("xml_parse")-1);
    if (ori_xml_parse_func && ori_xml_parse_func->type == ZEND_INTERNAL_FUNCTION) {
        ori_xml_parse_handler = ori_xml_parse_func->internal_function.handler;
        ori_xml_parse_func->internal_function.handler = (zif_handler)generic_xxe_handler;
    }
}
