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


#ifndef UTILS_H
#define UTILS_H

#include "php.h"
#include "../libcjson/cJSON.h"

char *concatenate_zval_array_into_string(zval *array_zv);
char *get_input_superglobal(zval *type_zv);
unsigned is_op_originates_from_const(uint32_t var_num, zend_op * opline, const zend_op_array *op_array);
int is_func_param_string_literal(zval *param, zend_execute_data *execute_data);
char *get_return_value_string(zval *return_value);

//extract function name, e.g. myexception() from a string, e.g. error message.
char* extract_function_name(const char *string);
char *extract_from_message(const char *msg);
char *extract_from_stack_trace(const char *msg);
void normalize_method_name(char *func_name); 
char *get_func_param_string(zval *param);

//this is for logging comparison involving user inputs
zend_string *is_zval_in_superglobal(zval *zv, uint8_t opcode);   //called from conditional_statement_handler in zimpaf main module.
zend_string *is_zval_value_in_array(zval *zv, zval *array_zv, uint8_t opcode);
void log_request_param_comparison(zend_string *op1_input_param, zend_string *op2_input_param, 
                                int result, zval *op1, uint8_t opcode, zval *op2,
                                char *filename, uint32_t lineno);
void add_zval_value_info_to_cJSON_object(zval *zv, cJSON *object, char *key_value, char *key_data_type);

#endif
