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

#include "php.h"
#include "zend_hash.h"
#include "zend_API.h"

char * get_http_header(const char *header_name) {      //HTTP_ is appended automatically
    zval *server_vars, *header_value;
    HashTable *server_hash;

    //Ensure $_SERVER is populated
    if (!zend_is_auto_global_str(ZEND_STRL("_SERVER"))) {
        zend_is_auto_global_str(ZEND_STRL("_SERVER"));
        // return NULL;
    }
    // Retrieve $_SERVER from symbol table
    server_vars = zend_hash_str_find(&EG(symbol_table), "_SERVER", sizeof("_SERVER") - 1);
    if (!server_vars || Z_TYPE_P(server_vars) != IS_ARRAY) {
        return NULL;
    }
    server_hash = Z_ARRVAL_P(server_vars);

    //Example: Retrieve "User-Agent" header (stored as HTTP_USER_AGENT in $_SERVER)
    if ((header_value = zend_hash_str_find(server_hash, header_name, strlen(header_name))) != NULL) {
        php_printf("User-Agent: %s\n", Z_STRVAL_P(header_value));
    }
    if(header_value == NULL) {
        php_printf("Header not found: %s\n", header_name);
        return NULL;
    }else{
        return Z_STRVAL_P(header_value);
    }
}
