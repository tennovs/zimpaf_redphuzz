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
#ifdef HAVE_CONFIG_H
# include "config.h"
#endif

#include<fcntl.h>
#include<unistd.h>
#include "php.h"
#include "SAPI.h"
#include "zend_observer.h"
#include "zend_exceptions.h"	
#include "ext/standard/info.h"
#include "php_zimpaf.h"
#include "zimpaf_arginfo.h"
#include "include/request_retrieval.h"
#include "include/dbhook.h"
#include "include/sanithook.h"
#include "include/deserhook.h"
#include "include/codexechook.h"
#include "include/dirtravshook.h"
#include "include/xxehook.h"
#include "include/error_exception_hook.h"
#include "include/utils.h"
#include "zend_hrtime.h"


/* For compatibility with older PHP versions */
#ifndef ZEND_PARSE_PARAMETERS_NONE
#define ZEND_PARSE_PARAMETERS_NONE() \
	ZEND_PARSE_PARAMETERS_START(0, 0) \
	ZEND_PARSE_PARAMETERS_END()
#endif

ZEND_DECLARE_MODULE_GLOBALS(zimpaf)

// JMP-variants hook
static user_opcode_handler_t original_jmp_handler;
static user_opcode_handler_t original_jmpz_handler;
static user_opcode_handler_t original_jmpnz_handler;
static user_opcode_handler_t original_jmpz_ex_handler;
static user_opcode_handler_t original_jmpnz_ex_handler;
static user_opcode_handler_t original_jmp_null_handler;
// static user_opcode_handler_t original_jmp_set_handler;

// IS-variants hook
static user_opcode_handler_t original_is_equal_handler;
static user_opcode_handler_t original_is_not_equal_handler;
static user_opcode_handler_t original_is_identical_handler;
static user_opcode_handler_t original_is_not_identical_handler;
static user_opcode_handler_t original_is_smaller_handler;
static user_opcode_handler_t original_is_smaller_equal_handler;

//CASE hook
static user_opcode_handler_t original_case_handler;
static user_opcode_handler_t original_case_strict_handler;

//INCLUDE_OR_EVAL hook
static user_opcode_handler_t original_include_or_eval_handler;
//EXIT hook
static user_opcode_handler_t original_exit_handler;
//for holding the value of handler used in conditional_statement_handler, generic_lang_construct handler
	
static user_opcode_handler_t original_throw_handler;

//to get which opcode handler is active for early termination when request is not for fuzzing or request without coverage_id
static user_opcode_handler_t get_original_opcode_handler(const zend_op *opline);

zval* get_zval_op(zend_execute_data *execute_data, const zend_op *opline, const znode_op op, uint8_t type);
zval* get_op1(zend_execute_data *execute_data,const zend_op *opline);
zval* get_op2(zend_execute_data *execute_data,const zend_op *opline);
void print_op(zval *op, unsigned int op_id);
int is_met_path_condition(zval *op1, zval *op2, unsigned int opcode);
int conditional_statement_handler(zend_execute_data *execute_data);

unsigned int is_include_or_require_path_constant(const zend_op *opline, zend_op_array *op_array);
// unsigned is_op_originates_from_const(uint32_t var_num, zend_op * opline, const zend_op_array *op_array);
void clear_path_table();
void realloc_path_table();

void clear_path_table() {
	#if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
	for (int i = 0; i < ZIMPAF_G(path_table_size); i++) {
		memset(ZIMPAF_G(path_table[i]), 0, MAX_CHARS);  // Set all to '\0'
	}
	for(int i = 0; i < LENGTH_FNAME; i++){
		ZIMPAF_G(cur_filename[i]) = '\0';
	}
	ZIMPAF_G(pt_cur_rows) = -1;				// Reset the current row in path table
	ZIMPAF_G(pt_cur_rows_size) = MAX_CHARS;	
	ZIMPAF_G(prev_lineno) = -1;				// Reset the previous line number
	ZIMPAF_G(prev_path_condition) = -1000;	// Reset the previous path condition
}

void realloc_path_table(){
	#if defined(ZTS) && defined(COMPILE_DL_TEST)
		ZEND_TSRMLS_CACHE_UPDATE();
	#endif
	unsigned int prev_size = ZIMPAF_G(path_table_size);
	ZIMPAF_G(path_table_size) += MAX_FILES;	// Increase the size of path table by MAX_FILES
	ZIMPAF_G(path_table) = perealloc(ZIMPAF_G(path_table), ZIMPAF_G(path_table_size) * sizeof(char *), 1);
	for (size_t i = prev_size; i < ZIMPAF_G(path_table_size); i++) {
    	ZIMPAF_G(path_table)[i] = pemalloc(MAX_CHARS * sizeof(char), 1);
    	memset(ZIMPAF_G(path_table)[i], 0, MAX_CHARS * sizeof(char));
	}
}

zval* get_zval_op(zend_execute_data *execute_data, const zend_op *opline, const znode_op op, uint8_t type) {
	#if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
	zval *op_zv = NULL;

    switch (type) {							// Determine how the operand is stored
        case IS_CONST:
            op_zv = RT_CONSTANT(opline, op);
            break;
        case IS_CV:
        case IS_VAR:
        case IS_TMP_VAR:
            op_zv = EX_VAR(op.var);
            break;
        case IS_UNUSED:
            return NULL; // No meaningful value
        // default:
		// 	ZEND_ASSERT(0 && "Unhandled operand type");
		// 	return NULL;
    }
    // Dereference if it's a reference
    if (op_zv && Z_TYPE_P(op_zv) == IS_REFERENCE) {
        op_zv = Z_REFVAL_P(op_zv);
    }
    return op_zv;
}

zval* get_op1(zend_execute_data *execute_data, const zend_op *opline) {
	#if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
	zval *op1 = NULL;
	
    switch (opline->op1_type) {							// Determine how the operand is stored
        case IS_CONST:
            op1 = RT_CONSTANT(opline, opline->op1);
            break;
        case IS_CV:
        case IS_VAR:
        case IS_TMP_VAR:
            op1 = EX_VAR(opline->op1.var);
            break;
        case IS_UNUSED:
            return NULL; // No meaningful value
        default:
			return NULL;
    }
    // Dereference if it's a reference
    if (op1 && Z_TYPE_P(op1) == IS_REFERENCE) {
        op1 = Z_REFVAL_P(op1);
    }
    return op1;
}

zval* get_op2(zend_execute_data *execute_data, const zend_op *opline) {
	#if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
	zval *op2 = NULL;

    switch (opline->op2_type) {							// Determine how the operand is stored
        case IS_CONST:
            op2 = RT_CONSTANT(opline, opline->op2);
            break;
        case IS_CV:
        case IS_VAR:
        case IS_TMP_VAR:
            op2 = EX_VAR(opline->op2.var);
            break;
        case IS_UNUSED:
            return NULL; // No meaningful value
        default:
			return NULL;
    }
    // Dereference if it's a reference
    if (op2 && Z_TYPE_P(op2) == IS_REFERENCE) {
        op2 = Z_REFVAL_P(op2);
    }
    return op2;
}

void print_op(zval *op, unsigned int op_id){
	if(op != NULL){
		switch (Z_TYPE_P(op)) {
			case IS_NULL:
				printf("op%u = NULL\n", op_id);
				break;
			case IS_FALSE:
				printf("op%u = false\n",op_id);
				break;
			case IS_TRUE:
				printf("op%u = true\n", op_id);
				break;
			case IS_LONG:
				printf("op%u = %ld\n", op_id,Z_LVAL_P(op));
				break;
			case IS_DOUBLE:
				printf("op%u = %f\n", op_id, Z_DVAL_P(op));
				break;
			case IS_STRING:
				printf("op%u = \"%s\"\n",op_id, Z_STRVAL_P(op));
				break;
			case IS_ARRAY:
				printf("op%u is array at: %p\n", op_id, Z_ARRVAL_P(op));
				break;
			case IS_OBJECT:
				printf("op%u is object-%s at: %p\n", op_id, Z_OBJCE_P(op)->name->val, Z_OBJ_P(op));
				break;
			case IS_RESOURCE:
				zend_resource *res = Z_RES_P(op);
				int handle = Z_RES_HANDLE_P(op);
				const char *type_name = zend_rsrc_list_get_rsrc_type(res);
				printf("op%u is resource-%s at: %ld\n", op_id, type_name, Z_RES_HANDLE_P(op));
				break;
			case IS_REFERENCE:
				printf("op%u is reference at: %p\n", op_id, Z_REF_P(op));
				break;
			case IS_UNDEF:
			    printf("op%u TYPE: is UNDEF yet.\n", op_id);
			    break;
			default:
				printf("op%u TYPE: is invalid yet.\n", op_id);
				break;
		}
	}
}

int is_met_path_condition(zval *op1, zval *op2, unsigned int opcode){
	#if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
	zval result;
	ZVAL_UNDEF(&result);
	int cmp_result = -1;

	if(compare_function(&result, op1, op2) == SUCCESS){
		cmp_result = Z_LVAL(result);
		int ret;
		switch (opcode){
			case ZEND_IS_EQUAL:
				ret = (cmp_result == 0);
				break;
			case ZEND_IS_NOT_EQUAL:
			case ZEND_IS_NOT_IDENTICAL:
				ret = (cmp_result != 0);
				break;
			case ZEND_IS_IDENTICAL:
				ret = zend_is_identical(op1, op2);
				break;
			case ZEND_IS_SMALLER:
				ret = (cmp_result < 0);
				break;
			case ZEND_IS_SMALLER_OR_EQUAL:
				ret = (cmp_result <= 0);
				break;
			case ZEND_CASE:
				ret = (cmp_result == 0);
				break;
			case ZEND_CASE_STRICT:
				ret = zend_is_identical(op1, op2);
				break;
			default:
				ret = -1000; 
		}
		zval_dtor(&result);
		return ret;
	}
	return 0;
}

static user_opcode_handler_t get_original_opcode_handler(const zend_op *opline){
    user_opcode_handler_t original_handler = NULL;

    switch (opline->opcode){
		case ZEND_JMP:
			original_handler = original_jmp_handler;
			break;
		case ZEND_JMPZ:
			original_handler = original_jmpz_handler;
			break;
		case ZEND_JMPNZ:
			original_handler = original_jmpnz_handler;
			break;
		case ZEND_JMPZ_EX:
			original_handler = original_jmpz_ex_handler;
			break;
		case ZEND_JMPNZ_EX:
			original_handler = original_jmpnz_ex_handler;
			break;
		case ZEND_JMP_NULL:
			original_handler = original_jmp_null_handler;
			break;
		// case ZEND_JMP_SET:
		// 	printf("Intercepted ZEND_JMP_SET at line %d\n", opline->lineno);
		// 	original_handler = original_jmp_set_handler;
		// 	break;
		case ZEND_IS_EQUAL:
			original_handler = original_is_equal_handler;
			break;
		case ZEND_IS_NOT_EQUAL:
			original_handler = original_is_not_equal_handler;
			break;
		case ZEND_IS_IDENTICAL:
			original_handler = original_is_identical_handler;
			break;
		case ZEND_IS_NOT_IDENTICAL:
			original_handler = original_is_not_identical_handler;
			break;
		case ZEND_IS_SMALLER:
			original_handler = original_is_smaller_handler;
			break;
		case ZEND_IS_SMALLER_OR_EQUAL:
			original_handler = original_is_smaller_equal_handler;
			break;
		case ZEND_CASE:
			original_handler = original_case_handler;
			break;
		case ZEND_CASE_STRICT:
			original_handler = original_case_strict_handler;
			break;
	}
    return original_handler;
}

int conditional_statement_handler(zend_execute_data *execute_data){
	#if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif

	char *filename = execute_data->func->op_array.filename->val;
	if(ZIMPAF_G(coverage_id) == NULL || !strstr(filename,"/var/www/html/")){
		user_opcode_handler_t opcode_original_handler = NULL;
		opcode_original_handler = get_original_opcode_handler(execute_data->opline);

		if(opcode_original_handler){
			return opcode_original_handler(execute_data);
		}else{
			//default dispatch
			return ZEND_USER_OPCODE_DISPATCH;
		}
	}
	
	zval *op1 = NULL;
	zval *op2 = NULL;
	int path_condition = -1000;
	
	user_opcode_handler_t original_handler = NULL;
	const zend_op *opline = execute_data->opline;
	uint32_t lineno = opline->lineno;
	zend_string *op1_input_param = NULL;
	zend_string *op2_input_param = NULL;
	uint8_t opcode = opline->opcode;
	uint8_t input_cmp_opcode = 0;
	printf("Executing statement at %s:%d\n", filename, lineno);
	op1 = get_op1(execute_data,opline);
	op1_input_param = is_zval_in_superglobal(op1, opcode);
	switch (opcode){
		case ZEND_JMP:
			printf("Intercepted ZEND_JMP at line %d\n", opline->lineno);
			original_handler = original_jmp_handler;
			path_condition = 1;
			break;
		case ZEND_JMPZ:
			printf("Intercepted ZEND_JMPZ at line %d\n", opline->lineno);
			printf("zend_is_true(op1)=%d\n",zend_is_true(op1));
			path_condition = zend_is_true(op1);
			printf("path_condition=%d\n",path_condition);
			original_handler = original_jmpz_handler;
			break;
		case ZEND_JMPNZ:
			printf("Intercepted ZEND_JMPNZ at line %d\n", opline->lineno);
			printf("zend_is_true(op1)=%d\n",zend_is_true(op1));
			path_condition = (zend_is_true(op1) == 0) ? 1 : 0;
			printf("path_condition=%d\n",path_condition);
			original_handler = original_jmpnz_handler;
			break;
		case ZEND_JMPZ_EX:
			printf("Intercepted ZEND_JMPZ_EX at line %d\n", opline->lineno);
			printf("zend_is_true(op1)=%d\n",zend_is_true(op1));
			path_condition = zend_is_true(op1);
			printf("path_condition=%d\n",path_condition);
			original_handler = original_jmpz_ex_handler;
			break;
		case ZEND_JMPNZ_EX:
			printf("Intercepted ZEND_JMPNZ_EX at line %d\n", opline->lineno);
			printf("zend_is_true(op1)=%d\n",zend_is_true(op1));
			path_condition = (zend_is_true(op1) == 0) ? 1 : 0;
			printf("path_condition=%d\n",path_condition);
			original_handler = original_jmpnz_ex_handler;
			break;
		case ZEND_JMP_NULL:
			printf("Intercepted ZEND_JMP_NULL at line %d\n", opline->lineno);
			printf("zend_is_true(op1)=%d\n",zend_is_true(op1));
			path_condition = zend_is_true(op1);
			printf("path_condition=%d\n",path_condition);
			original_handler = original_jmp_null_handler;
			break;
		// case ZEND_JMP_SET:
		// 	printf("Intercepted ZEND_JMP_SET at line %d\n", opline->lineno);
		// 	original_handler = original_jmp_set_handler;
		// 	break;
		case ZEND_IS_EQUAL:
			printf("Intercepted ZEND_IS_EQUAL at line %d\n", opline->lineno);
			op2 = get_op2(execute_data,opline);
			print_op(op1,1);
			print_op(op2,2);
			path_condition = is_met_path_condition(op1, op2, ZEND_IS_EQUAL);
			printf("path_condition=%d\n",path_condition);
			op2_input_param = is_zval_in_superglobal(op2, opcode);
			input_cmp_opcode = ZEND_IS_EQUAL;
			original_handler = original_is_equal_handler;
			break;
		case ZEND_IS_NOT_EQUAL:
			printf("Intercepted ZEND_IS_NOT_EQUAL at line %d\n", opline->lineno);
			op2 = get_op2(execute_data,opline);
			print_op(op1,1);
			print_op(op2,2);
			path_condition = is_met_path_condition(op1, op2, ZEND_IS_NOT_EQUAL);
			printf("path_condition=%d\n",path_condition);
			op2_input_param = is_zval_in_superglobal(op2, opcode);
			input_cmp_opcode = ZEND_IS_NOT_EQUAL;
			original_handler = original_is_not_equal_handler;
			break;
		case ZEND_IS_IDENTICAL:
			printf("Intercepted ZEND_IS_IDENTICAL at line %d\n", opline->lineno);
			op2 = get_op2(execute_data,opline);
			print_op(op1,1);
			print_op(op2,2);
			path_condition = is_met_path_condition(op1, op2, ZEND_IS_IDENTICAL);
			printf("path_condition=%d\n",path_condition);
			op2_input_param = is_zval_in_superglobal(op2, opcode);
			input_cmp_opcode = ZEND_IS_IDENTICAL;
			original_handler = original_is_identical_handler;
			break;
		case ZEND_IS_NOT_IDENTICAL:
			printf("Intercepted ZEND_IS_NOT_IDENTICAL at line %d\n", opline->lineno);
			op2 = get_op2(execute_data,opline);
			print_op(op1,1);
			print_op(op2,2);
			path_condition = is_met_path_condition(op1, op2, ZEND_IS_NOT_IDENTICAL);
			printf("path_condition=%d\n",path_condition);
			op2_input_param = is_zval_in_superglobal(op2, opcode);
			input_cmp_opcode = ZEND_IS_NOT_IDENTICAL;
			original_handler = original_is_not_identical_handler;
			break;
		case ZEND_IS_SMALLER:
			printf("Intercepted ZEND_IS_SMALLER at line %d\n", opline->lineno);
			op2 = get_op2(execute_data,opline);
			print_op(op1,1);
			print_op(op2,2);
			path_condition = is_met_path_condition(op1, op2, ZEND_IS_SMALLER);
			printf("path_condition=%d\n",path_condition);
			op2_input_param = is_zval_in_superglobal(op2, opcode);
			input_cmp_opcode = ZEND_IS_SMALLER;
			original_handler = original_is_smaller_handler;
			break;
		case ZEND_IS_SMALLER_OR_EQUAL:
			printf("Intercepted ZEND_IS_SMALLER_EQUAL at line %d\n", opline->lineno);
			op2 = get_op2(execute_data,opline);
			print_op(op1,1);
			print_op(op2,2);
			path_condition = is_met_path_condition(op1, op2, ZEND_IS_SMALLER_OR_EQUAL);
			printf("path_condition=%d\n",path_condition);
			op2_input_param = is_zval_in_superglobal(op2, opcode);
			input_cmp_opcode = ZEND_IS_SMALLER_OR_EQUAL;
			original_handler = original_is_smaller_equal_handler;
			break;
		case ZEND_CASE:
			printf("Intercepted ZEND_CASE at line %d\n", opline->lineno);
			op2 = get_op2(execute_data,opline);
			print_op(op1,1);
			print_op(op2,2);
			path_condition = is_met_path_condition(op1, op2, ZEND_CASE);
			printf("path_condition=%d\n",path_condition);
			op2_input_param = is_zval_in_superglobal(op2, opcode);
			input_cmp_opcode = ZEND_CASE;
			original_handler = original_case_handler;
			break;
		case ZEND_CASE_STRICT:
			printf("Intercepted ZEND_CASE_STRICT at line %d\n", opline->lineno);
			op2 = get_op2(execute_data,opline);
			print_op(op1,1);
			print_op(op2,2);
			path_condition = is_met_path_condition(op1, op2, ZEND_CASE_STRICT);
			printf("path_condition=%d\n",path_condition);
			op2_input_param = is_zval_in_superglobal(op2, opcode);
			input_cmp_opcode = ZEND_CASE_STRICT;
			original_handler = original_case_strict_handler;
			break;
		default:
			return ZEND_USER_OPCODE_DISPATCH;
	}
	
	//store code coverage information into path table
	if(strcmp(ZIMPAF_G(cur_filename),filename) != 0){
		ZIMPAF_G(pt_cur_rows)++;					//initially -1 see clear_path_table()
		ZIMPAF_G(pt_cur_rows_size) = MAX_CHARS;		// Reset the size of current row in path table, in case it was modified by condition statement below
		if(ZIMPAF_G(pt_cur_rows) > ZIMPAF_G(path_table_size) - 1){
			realloc_path_table();
		}  					
		strcpy(ZIMPAF_G(cur_filename),filename);
		strcat(ZIMPAF_G(path_table)[ZIMPAF_G(pt_cur_rows)],filename);
		strcat(ZIMPAF_G(path_table)[ZIMPAF_G(pt_cur_rows)],"::::");
		size_t current_len = strlen(ZIMPAF_G(path_table)[ZIMPAF_G(pt_cur_rows)]);
		size_t remaining = ZIMPAF_G(pt_cur_rows_size) - current_len;

		if (remaining < 16) { // 16 is safe for "_12345-0\0"
			ZIMPAF_G(pt_cur_rows_size) += MAX_CHARS;
			ZIMPAF_G(path_table)[ZIMPAF_G(pt_cur_rows)] = perealloc(ZIMPAF_G(path_table)[ZIMPAF_G(pt_cur_rows)],
																		ZIMPAF_G(pt_cur_rows_size),
																		1
																		);
		}
		snprintf(ZIMPAF_G(path_table)[ZIMPAF_G(pt_cur_rows)] + 
		strlen(ZIMPAF_G(path_table)[ZIMPAF_G(pt_cur_rows)]),
		ZIMPAF_G(pt_cur_rows_size)-strlen(ZIMPAF_G(path_table)[ZIMPAF_G(pt_cur_rows)]),
		"%u-%d",lineno, path_condition);
		ZIMPAF_G(prev_lineno) = lineno;
		ZIMPAF_G(prev_path_condition) = path_condition;
	}else{
		size_t current_len = strlen(ZIMPAF_G(path_table)[ZIMPAF_G(pt_cur_rows)]);
		size_t remaining = ZIMPAF_G(pt_cur_rows_size) - current_len;

		if (remaining < 16) { // 16 is safe for "_12345-0\0"
			ZIMPAF_G(pt_cur_rows_size) += MAX_CHARS;
			ZIMPAF_G(path_table)[ZIMPAF_G(pt_cur_rows)] = perealloc(ZIMPAF_G(path_table)[ZIMPAF_G(pt_cur_rows)],
																			ZIMPAF_G(pt_cur_rows_size),
																			1
																			);
		}
		snprintf(ZIMPAF_G(path_table)[ZIMPAF_G(pt_cur_rows)] + 
		strlen(ZIMPAF_G(path_table)[ZIMPAF_G(pt_cur_rows)]),
		ZIMPAF_G(pt_cur_rows_size)-strlen(ZIMPAF_G(path_table)[ZIMPAF_G(pt_cur_rows)]),"_%u-%d",lineno, path_condition);
		ZIMPAF_G(prev_lineno) = lineno;
		ZIMPAF_G(prev_path_condition) = path_condition;
	}
	//store input comparison
	// zval *result = get_zval_op(execute_data, opline, opline->result, opline->result_type);
	if(op1_input_param || op2_input_param){
		//call log function
		log_request_param_comparison(op1_input_param, op2_input_param, //op1 and op2 input param
									 path_condition, op1, opline->opcode, op2,		   //op1 opcode op2
									 filename,lineno);				   //location: filename lineno	
	}
	//call the original handler
	if(original_handler){
		return original_handler(execute_data);
	}
	//default dispatch
	return ZEND_USER_OPCODE_DISPATCH;
}

int generic_lang_construct_handler(zend_execute_data *execute_data){
	#if defined(ZTS) && defined(COMPILE_DL_TEST)
		ZEND_TSRMLS_CACHE_UPDATE();
	#endif

	char *filename = execute_data->func->op_array.filename->val;
	const zend_op *opline = execute_data->opline;
	if(ZIMPAF_G(coverage_id) == NULL || !strstr(filename,"/var/www/html/")){
		if(opline->opcode == ZEND_INCLUDE_OR_EVAL && original_include_or_eval_handler){
			return original_include_or_eval_handler(execute_data);
		}else if(opline->opcode == ZEND_EXIT && original_exit_handler){
			return original_exit_handler(execute_data);
		}else{
			//default dispatch
			return ZEND_USER_OPCODE_DISPATCH;
		}
	}
	
	user_opcode_handler_t original_handler = NULL;
	char function_name[64] ={0};
	zval *param_zv = NULL;
	uint32_t lineno = opline->lineno;
	printf("Executing statement at %s:%d\n", filename, lineno);
	const zend_op_array *op_array = &execute_data->func->op_array;
	unsigned int path_opline_type = 0;
	unsigned int cmd_opline_type = 0;
	switch (opline->opcode) {
		case ZEND_INCLUDE_OR_EVAL:
			if(opline->extended_value == ZEND_EVAL){
				strcpy(function_name, "eval");
				param_zv = ZEND_CALL_ARG(execute_data, 1);
				// cmd_opline_type = path_opline_type = is_func_param_string_literal(param_zv, execute_data);
				cmd_opline_type = path_opline_type =is_include_or_require_path_constant(opline, (zend_op_array *)op_array);
			}else if(opline->extended_value == ZEND_INCLUDE){
				strcpy(function_name, "include");
				param_zv = ZEND_CALL_ARG(execute_data, 2);
				path_opline_type = is_include_or_require_path_constant(opline, (zend_op_array *)op_array);
			}else if(opline->extended_value == ZEND_INCLUDE_ONCE){
				strcpy(function_name, "include_once");
				param_zv =ZEND_CALL_ARG(execute_data, 2);
				path_opline_type = is_include_or_require_path_constant(opline, (zend_op_array *)op_array);
			}else if(opline->extended_value == ZEND_REQUIRE){
				strcpy(function_name, "require");
				param_zv = ZEND_CALL_ARG(execute_data, 2);
				path_opline_type = is_include_or_require_path_constant(opline, (zend_op_array *)op_array);
			}else if(opline->extended_value == ZEND_REQUIRE_ONCE){
				strcpy(function_name, "require_once");
				param_zv = ZEND_CALL_ARG(execute_data, 2);
				path_opline_type = is_include_or_require_path_constant(opline, (zend_op_array *)op_array);
			}
			printf("Intercepted %s(...) by ZEND_INCLUDE_OR_EVAL at line %d\n", function_name,opline->lineno);
			original_handler = original_include_or_eval_handler;
			break;
		case ZEND_EXIT:
			strcpy(function_name, "die or exit");
			char message[] = "";
			if(opline->op1_type != IS_UNUSED){
				param_zv = get_op1(execute_data, opline);
			}
			printf("Intercepted %s(...) by ZEND_EXIT at line %d\n", function_name,opline->lineno);
			original_handler = original_exit_handler;
			break;
		default:
			return ZEND_USER_OPCODE_DISPATCH; // For other opcodes, we can just return the default dispatch
	}
	zval *op1_zv = NULL;
	const char *op1_param = NULL;
	if (opline->op1_type == IS_CONST) {
		op1_zv = RT_CONSTANT(opline, opline->op1);
	} else if (opline->op1_type == IS_VAR || opline->op1_type == IS_TMP_VAR || opline->op1_type == IS_CV) {
		op1_zv = EX_VAR(opline->op1.var);
	}

	unsigned int len_param=0;
	const char *param_str;
	if(op1_zv && (Z_TYPE_P(op1_zv) == IS_STRING)) { //&& (Z_STRLEN_P(op1_zv) >= Z_STRLEN_P(param_zv))){
		len_param = Z_STRLEN_P(op1_zv);
		param_str = Z_STRVAL_P(op1_zv);	
	}else if (param_zv && Z_TYPE_P(param_zv) == IS_STRING) {
		len_param = Z_STRLEN_P(param_zv);
		param_str = Z_STRVAL_P(param_zv);
	} else {
		len_param = 0;
		param_str = "";
	}

	unsigned int len_funcname_param = strlen(function_name) + len_param + 1;
	char funcname_param[len_funcname_param];
	snprintf(funcname_param, len_funcname_param, "%s_%s", function_name, param_str);
	zend_ulong current_hash = zend_inline_hash_func(funcname_param, strlen(funcname_param));
	zend_ulong x = ZIMPAF_G(last_hash); //just to check the value
	if(current_hash == ZIMPAF_G(last_hash)) {
		if(original_handler){
			return original_handler(execute_data);
		}
		return ZEND_USER_OPCODE_DISPATCH;
	}

	ZIMPAF_G(last_hash) = current_hash;
	zend_ulong y = ZIMPAF_G(last_hash);	//just to check
	// zval *res = get_zval_op(execute_data, opline, opline->result, opline->result_type);
	// char *res_string = get_return_value_string(res);

	cJSON *func_call = cJSON_CreateObject();
	cJSON_AddStringToObject(func_call, "function_name", function_name);
	if(opline->extended_value == ZEND_EVAL){
		cJSON_AddStringToObject(func_call, "command", param_str);
		cJSON_AddNumberToObject(func_call, "sink_opline_type", cmd_opline_type);
	}else{
		cJSON_AddStringToObject(func_call, "path", param_str);
		cJSON_AddNumberToObject(func_call, "sink_opline_type", path_opline_type);	
	}
	
    cJSON_AddStringToObject(func_call, "filename", filename);
    cJSON_AddNumberToObject(func_call, "lineno", opline->lineno);
    cJSON_AddItemToArray(ZIMPAF_G(func_call_seq), func_call);
	
	int ret;
	if(original_handler){
		ret = original_handler(execute_data);
	}else {
	//default dispatch
		ret = ZEND_USER_OPCODE_DISPATCH;
	}

	// zval *eval_result = get_zval_op(execute_data, opline, opline->result, opline->result_type);
	//The below check is only intended for include or require.
	zend_string *err_msg = PG(last_error_message);
	if(ZIMPAF_G(last_error_msg) != PG(last_error_message)){
		cJSON_AddStringToObject(func_call, "return_value", "");
		ZIMPAF_G(last_error_msg) = PG(last_error_message);
	}else{
		cJSON_AddStringToObject(func_call, "return_value", "1");
	}

	return ret;
}

unsigned int is_include_or_require_path_constant(const zend_op *opline, zend_op_array *op_array) {
	#if defined(ZTS) && defined(COMPILE_DL_TEST)
		ZEND_TSRMLS_CACHE_UPDATE();
	#endif
	unsigned int path_opline_type = opline->op1_type; // Default to the original type
	zend_op *prev_opline =NULL;
	zend_op *prev_prev_opline = NULL;
	
	if (opline->op1_type == IS_CONST){
		return IS_CONST;
	}else if(opline->op1_type == IS_VAR || opline->op1_type == IS_TMP_VAR || opline->op1_type == IS_CV){
		uint32_t op1_var_num = EX_VAR_TO_NUM(opline->op1.var);
		if (opline > op_array->opcodes) { // Get the previous opcode to check for concatenation
			prev_opline = (zend_op *)opline - 1;
		}else{
			return 0;
		}
		if(prev_opline && prev_opline->opcode == ZEND_FETCH_CONSTANT){
			return IS_CONST;
		}else if(prev_opline && (prev_opline->opcode == ZEND_ASSIGN || 
								 prev_opline->opcode == ZEND_QM_ASSIGN || 
								 prev_opline->opcode == ZEND_ROPE_INIT)){
			if (prev_opline->op2_type == IS_CONST)  {
				return IS_CONST; // The variable is assigned a constant value
			} else if (prev_opline->op2_type == IS_TMP_VAR || prev_opline->op2_type == IS_VAR 
														|| prev_opline->op2_type == IS_CV) {
				return is_op_originates_from_const(EX_VAR_TO_NUM(prev_opline->op2.var), prev_opline, op_array);
			}
		}else if (prev_opline && (prev_opline->opcode == ZEND_CONCAT || 
								  prev_opline->opcode == ZEND_FAST_CONCAT ||
								  prev_opline->opcode == ZEND_ROPE_ADD ||
								  prev_opline->opcode == ZEND_ROPE_END)){
			int op1_const = (prev_opline->op1_type == IS_CONST);
			int op2_const = (prev_opline->op2_type == IS_CONST);

			if (!op1_const && (prev_opline->op1_type == IS_TMP_VAR 
							|| prev_opline->op1_type == IS_VAR 
							|| prev_opline->op1_type == IS_CV)) {
				op1_const = is_op_originates_from_const(EX_VAR_TO_NUM(prev_opline->op1.var), prev_opline, op_array);
			}

			if (!op2_const && (prev_opline->op2_type == IS_TMP_VAR 
								|| prev_opline->op2_type == IS_VAR 
								|| prev_opline->op2_type == IS_CV)) {
				op2_const = is_op_originates_from_const(EX_VAR_TO_NUM(prev_opline->op2.var), prev_opline, op_array);
			}
			if(op1_const == IS_CONST && op2_const == IS_CONST){
				return 1;
			}else{
				return 0;
			}  
		}
	}		
	return path_opline_type;
}

int exception_via_zend_throw_handler(zend_execute_data *execute_data){
	#if defined(ZTS) && defined(COMPILE_DL_TEST)
		ZEND_TSRMLS_CACHE_UPDATE();
	#endif 

	if(ZIMPAF_G(coverage_id) == NULL){
		if(original_throw_handler){
			return original_throw_handler(execute_data);
		}else{
			//default dispatch
			return ZEND_USER_OPCODE_DISPATCH;
		}
	}

	zval *op1_zval;
	char *filename = execute_data->func->op_array.filename->val;
	unsigned int ex_already_logged = 0;
	if(strstr(filename,"/var/www/html/")){
		const zend_op *opline = execute_data->opline;
		uint32_t lineno = opline->lineno;
		printf("Executing statement at %s:%d\n", filename, lineno);

		op1_zval = get_op1(execute_data, opline);
		if (op1_zval && Z_TYPE_P(op1_zval) == IS_OBJECT) {
			zend_object *ex_obj = Z_OBJ_P(op1_zval); 
			if (instanceof_function(ex_obj->ce, zend_ce_exception)) {
				// Deduplication happens inside here using the ex_obj pointer
				zimpaf_observe_exception(ex_obj);
			}
		}
	}

	if(original_throw_handler){
		return original_throw_handler(execute_data);
	}
	//default dispatch
	return ZEND_USER_OPCODE_DISPATCH;
}


PHP_MINIT_FUNCTION(zimpaf){
	#if defined(ZTS) && defined(COMPILE_DL_ZIMPAF)
		ZEND_TSRMLS_CACHE_UPDATE();
	#endif

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

	/*Branch opcodes hook*/
	original_jmp_handler = zend_get_user_opcode_handler(ZEND_JMP);
	zend_set_user_opcode_handler(ZEND_JMP,conditional_statement_handler);
	original_jmpz_handler = zend_get_user_opcode_handler(ZEND_JMPZ);
	zend_set_user_opcode_handler(ZEND_JMPZ,conditional_statement_handler);
	original_jmpnz_handler = zend_get_user_opcode_handler(ZEND_JMPNZ);
	zend_set_user_opcode_handler(ZEND_JMPNZ,conditional_statement_handler);
	original_jmpz_ex_handler = zend_get_user_opcode_handler(ZEND_JMPZ_EX);
	zend_set_user_opcode_handler(ZEND_JMPZ_EX,conditional_statement_handler);
	original_jmpnz_ex_handler = zend_get_user_opcode_handler(ZEND_JMPNZ_EX);
	zend_set_user_opcode_handler(ZEND_JMPNZ_EX,conditional_statement_handler);
	original_jmp_null_handler = zend_get_user_opcode_handler(ZEND_JMP_NULL);
	zend_set_user_opcode_handler(ZEND_JMP_NULL,conditional_statement_handler);
	// original_jmp_set_handler = zend_get_user_opcode_handler(ZEND_JMP_SET);
	// zend_set_user_opcode_handler(ZEND_JMP_SET,conditional_statement_handler);

	/*IS-variants hook*/
	original_is_equal_handler = zend_get_user_opcode_handler(ZEND_IS_EQUAL);
	zend_set_user_opcode_handler(ZEND_IS_EQUAL,conditional_statement_handler);
	original_is_not_equal_handler = zend_get_user_opcode_handler(ZEND_IS_NOT_EQUAL);
	zend_set_user_opcode_handler(ZEND_IS_NOT_EQUAL,conditional_statement_handler);
	original_is_identical_handler = zend_get_user_opcode_handler(ZEND_IS_IDENTICAL);
	zend_set_user_opcode_handler(ZEND_IS_IDENTICAL,conditional_statement_handler);
	original_is_not_identical_handler = zend_get_user_opcode_handler(ZEND_IS_NOT_IDENTICAL);
	zend_set_user_opcode_handler(ZEND_IS_NOT_IDENTICAL,conditional_statement_handler);
	original_is_smaller_handler = zend_get_user_opcode_handler(ZEND_IS_SMALLER);
	zend_set_user_opcode_handler(ZEND_IS_SMALLER,conditional_statement_handler);
	original_is_smaller_equal_handler = zend_get_user_opcode_handler(ZEND_IS_SMALLER_OR_EQUAL);
	zend_set_user_opcode_handler(ZEND_IS_SMALLER_OR_EQUAL,conditional_statement_handler);

	/*CASE hook*/
	original_case_handler = zend_get_user_opcode_handler(ZEND_CASE);
	zend_set_user_opcode_handler(ZEND_CASE,conditional_statement_handler);
	original_case_strict_handler = zend_get_user_opcode_handler(ZEND_CASE_STRICT);
	zend_set_user_opcode_handler(ZEND_CASE_STRICT,conditional_statement_handler);
	/*INCLUDE_OR_EVAL hook*/
	original_include_or_eval_handler = zend_get_user_opcode_handler(ZEND_INCLUDE_OR_EVAL);
	zend_set_user_opcode_handler(ZEND_INCLUDE_OR_EVAL,generic_lang_construct_handler);

	original_exit_handler = zend_get_user_opcode_handler(ZEND_EXIT);
	zend_set_user_opcode_handler(ZEND_EXIT, generic_lang_construct_handler);	
	
	//for exception hook via ZEND_THROW opcode
	original_throw_handler = zend_get_user_opcode_handler(ZEND_THROW);
	zend_set_user_opcode_handler(ZEND_THROW, exception_via_zend_throw_handler);
	return SUCCESS;
}

// MSHUTDOWN to restore the original handler
PHP_MSHUTDOWN_FUNCTION(zimpaf) {
	#if defined(ZTS) && defined(COMPILE_DL_ZIMPAF)
		ZEND_TSRMLS_CACHE_UPDATE();
	#endif
    // Restore the original JMP-variants handler
	zend_set_user_opcode_handler(ZEND_JMP, original_jmp_handler);
	zend_set_user_opcode_handler(ZEND_JMPZ, original_jmpz_handler);
	zend_set_user_opcode_handler(ZEND_JMPNZ,original_jmpnz_handler);
	zend_set_user_opcode_handler(ZEND_JMPZ_EX,original_jmpz_ex_handler);
	zend_set_user_opcode_handler(ZEND_JMPNZ_EX, original_jmpnz_ex_handler);
	zend_set_user_opcode_handler(ZEND_JMP_NULL,original_jmp_null_handler);
	// zend_set_user_opcode_handler(ZEND_JMP_SET,original_jmp_set_handler);

	// Restore the original IS-variants handler
	zend_set_user_opcode_handler(ZEND_IS_EQUAL, original_is_equal_handler);
	zend_set_user_opcode_handler(ZEND_IS_NOT_EQUAL, original_is_not_equal_handler);
	zend_set_user_opcode_handler(ZEND_IS_IDENTICAL, original_is_identical_handler);
	zend_set_user_opcode_handler(ZEND_IS_NOT_IDENTICAL, original_is_not_identical_handler);
	zend_set_user_opcode_handler(ZEND_IS_SMALLER, original_is_smaller_handler);
	zend_set_user_opcode_handler(ZEND_IS_SMALLER_OR_EQUAL, original_is_smaller_equal_handler);

	// Restore the original CASE handler
	zend_set_user_opcode_handler(ZEND_CASE, original_case_handler);
	zend_set_user_opcode_handler(ZEND_CASE_STRICT, original_case_strict_handler);

	// Restore the original INCLUDE_OR_EVAL handler
	zend_set_user_opcode_handler(ZEND_INCLUDE_OR_EVAL, original_include_or_eval_handler);

	// Restore the original EXIT handler
	zend_set_user_opcode_handler(ZEND_EXIT, original_exit_handler);

	// Restore the original THROW handler
	zend_set_user_opcode_handler(ZEND_THROW, original_throw_handler);

	return SUCCESS;
}

/* {{{ PHP_RINIT_FUNCTION */
PHP_RINIT_FUNCTION(zimpaf){
#if defined(ZTS) && defined(COMPILE_DL_ZIMPAF)
ZEND_TSRMLS_CACHE_UPDATE();
#endif
	ZIMPAF_G(start_time) = zend_hrtime();

	ZIMPAF_G(coverage_id) = NULL;
	ZIMPAF_G(func_call_seq) = NULL;
	ZIMPAF_G(input_comparisons) = NULL;

	//These purpose is to avoid duplication of error or exception report caught by zimpaf_observe_exception, 
	//zimpaf_observer_error_handler, and zend_error_cb, see libhooks/error_exception_hook.c
	ZIMPAF_G(last_observed_ex) = NULL; //DON'T FREE !!!, holds zend last exception object logged/seen.
	ZIMPAF_G(error_exception_just_logged) = 0;
	ZIMPAF_G(error_exception_just_logged_funcname) = NULL;
	ZIMPAF_G(error_exception_just_logged_filename) = NULL;
	ZIMPAF_G(error_exception_just_logged_lineno) = -1;
	

	ZIMPAF_G(last_hash) = 0; 			//For deduplication in generic_lang_contrstruct_handler(...) in zimpaf.c
	ZIMPAF_G(last_input_cmp_hash) = 0;	//For deduplication in log_request_param_comparison(...), in utils.c
	ZIMPAF_G(last_error_msg) = NULL;

	char *coverid = NULL;
	const char *header = "HTTP_X_FUZZER_COVID";
	coverid = get_http_header(header);
	if(coverid != NULL){
		unsigned int length = strlen(coverid);
		ZIMPAF_G(coverage_id) = emalloc(length+1);
		memset(ZIMPAF_G(coverage_id), 0, length+1);
		strcpy(ZIMPAF_G(coverage_id), coverid);
		php_printf("Coverage-ID: %s\n", ZIMPAF_G(coverage_id));
	}
	/*Clear or reset path table to collect code coverate in terms of branch opcodes execution
	  Allocate cJSON array to hold the function calls
	  Allocate cJSON array to hold the input parameters comparisons
	*/
	if(ZIMPAF_G(coverage_id) != NULL){
		clear_path_table(ZIMPAF_G(path_table));
		ZIMPAF_G(func_call_seq) = cJSON_CreateArray();
		ZIMPAF_G(input_comparisons) = cJSON_CreateArray();
		
		ZIMPAF_G(orig_bailout) = EG(bailout);
		EG(bailout) = &ZIMPAF_G(bailout_buf);
		if (SETJMP(ZIMPAF_G(bailout_buf)) == 0) {
			ZIMPAF_G(bailout_triggered) = 0;
		} else {
			ZIMPAF_G(bailout_triggered) = 1;
		}
	}
	//  zend_hrtime_t start = zend_hrtime();
	return SUCCESS;
}

PHP_RSHUTDOWN_FUNCTION(zimpaf)
{
#if defined(ZTS) && defined(COMPILE_DL_ZIMPAF)
	ZEND_TSRMLS_CACHE_UPDATE();
#endif
	if(ZIMPAF_G(coverage_id) != NULL){
		if(ZIMPAF_G(bailout_triggered)) {
			fprintf(stderr, "Premature script termination (zend_bailout) detected!\n");
		}
		EG(bailout) = ZIMPAF_G(orig_bailout);  
	}
	unsigned int covid_len = ZIMPAF_G(coverage_id) ? strlen(ZIMPAF_G(coverage_id)) : 7; //7 is fow unknown string
	covid_len = covid_len + 1;
	char covid_name[covid_len];
	strcpy(covid_name, ZIMPAF_G(coverage_id) ? ZIMPAF_G(coverage_id) : "unknown");

	double start_logging = zend_hrtime();

	if(ZIMPAF_G(coverage_id) != NULL){
		char cocov_dir[] = "/shared-tmpfs/coverage-reports/";
		unsigned int len_cocov_fname = strlen(cocov_dir) + strlen("/")+strlen(ZIMPAF_G(coverage_id))+strlen(".json")+1;
		char cocov_fname[len_cocov_fname];
		snprintf(cocov_fname, len_cocov_fname, "%s/%s.json", cocov_dir, ZIMPAF_G(coverage_id));
		FILE *file = fopen(cocov_fname,"w");
		char **table = ZIMPAF_G(path_table);

		if(ZIMPAF_G(path_table) != NULL){
			//No branch instructions are intercepted, straight line execution in the script
			if(*table && **table == 0){
				//to make it 0, the same as in conditional stmt handler, at request initialization, 
				ZIMPAF_G(pt_cur_rows)++; //it set t0 -1 in clear_path_table()
				// unsigned int r = ZIMPAF_G(pt_cur_rows);
				fprintf(file, "[");
				char *filename = SG(request_info).path_translated;
				strcat(ZIMPAF_G(path_table)[ZIMPAF_G(pt_cur_rows)],filename);
				strcat(ZIMPAF_G(path_table)[ZIMPAF_G(pt_cur_rows)],"::::");
				for(int i = 0; i <= ZIMPAF_G(pt_cur_rows); i++){
					fprintf(file, "\"%s\"%s", ZIMPAF_G(path_table)[i], (i != ZIMPAF_G(pt_cur_rows)) ? ", " : "");
				}
				fprintf(file, "]\n");
				fclose(file);
			}
			else{
				fprintf(file, "[");
				for(int i = 0; i <= ZIMPAF_G(pt_cur_rows); i++){
					fprintf(file, "\"%s\"%s", ZIMPAF_G(path_table)[i], (i != ZIMPAF_G(pt_cur_rows)) ? ", " : "");
				}
				fprintf(file, "]\n");
				fclose(file);
				// write_coverage_report();
			}
		}
		if(ZIMPAF_G(func_call_seq) != NULL && cJSON_GetArraySize(ZIMPAF_G(func_call_seq)) > 0){
			char func_trace_dir[] = "/shared-tmpfs/function-call-traces";
			unsigned int len_func_trace_fname = strlen(func_trace_dir) + strlen("/")+strlen(ZIMPAF_G(coverage_id))+strlen(".json")+1;
			char func_trace_fname[len_func_trace_fname];
			snprintf(func_trace_fname, len_func_trace_fname, "%s/%s.json", func_trace_dir, ZIMPAF_G(coverage_id));
			char *json_string = cJSON_Print(ZIMPAF_G(func_call_seq)); // Use cJSON_PrintUnformatted() for compact output
			if(json_string!= NULL){
				printf("%s\n", json_string);
				FILE *f = fopen(func_trace_fname, "w");
				if (f) {
					fputs(json_string, f);
					fclose(f);
				}
			free(json_string); // Free the JSON string
			}
			cJSON_Delete(ZIMPAF_G(func_call_seq));
			ZIMPAF_G(func_call_seq) = NULL;
		}
		if(ZIMPAF_G(input_comparisons) != NULL && cJSON_GetArraySize(ZIMPAF_G(input_comparisons)) > 0){
			char input_comparisons_dir[] = "/shared-tmpfs/input_params_comparisons";
			unsigned int len_input_comparisons_fname = strlen(input_comparisons_dir) + strlen("/")+strlen(ZIMPAF_G(coverage_id))+strlen(".json")+1;
			char input_comparisons_fname[len_input_comparisons_fname];
			snprintf(input_comparisons_fname, len_input_comparisons_fname, "%s/%s.json", input_comparisons_dir, ZIMPAF_G(coverage_id));
			char *json_string = cJSON_Print(ZIMPAF_G(input_comparisons)); // Use cJSON_PrintUnformatted() for compact output
			if(json_string!= NULL){
				printf("%s\n", json_string);
				FILE *f = fopen(input_comparisons_fname, "w");
				if (f) {
					fputs(json_string, f);
					fclose(f);
				}
			free(json_string); // Free the JSON string
			}
			cJSON_Delete(ZIMPAF_G(input_comparisons));
			ZIMPAF_G(input_comparisons) = NULL;
		}
		efree(ZIMPAF_G(coverage_id));
		ZIMPAF_G(coverage_id) = NULL;
	}

	//Cleanup globals used for error/exception deduplication
	ZIMPAF_G(last_observed_ex) = NULL; //never free, holds zend last exception object logged/seen.
	
	if (ZIMPAF_G(error_exception_just_logged_filename)) {
        efree(ZIMPAF_G(error_exception_just_logged_filename));
        ZIMPAF_G(error_exception_just_logged_filename) = NULL;
    }
    
    if (ZIMPAF_G(error_exception_just_logged_funcname)) {
        efree(ZIMPAF_G(error_exception_just_logged_funcname));
        ZIMPAF_G(error_exception_just_logged_funcname) = NULL;
    }

    // 2. Reset the flags for the next request
    ZIMPAF_G(error_exception_just_logged) = 0;
    ZIMPAF_G(error_exception_just_logged_lineno) = 0;
	ZIMPAF_G(last_input_cmp_hash) = 0;

	ZIMPAF_G(end_time) = zend_hrtime();
	double start_sec = ZIMPAF_G(start_time) / 1000000000;
	double start_logging_sec = start_logging / 1000000000;
    double end_sec   = ZIMPAF_G(end_time) / 1000000000;
	char cocov_dir[] = "/shared-tmpfs/coverage-reports/";
	unsigned int len_time_fname = strlen(cocov_dir) + strlen("/")+strlen(covid_name)+ 5 //_time 
								  + strlen(".json")+1;
	char time_fname[len_time_fname];
	snprintf(time_fname, len_time_fname, "%s/%s_time.json", cocov_dir, covid_name);
	FILE *ftime = fopen(time_fname,"w");
	if (ftime){
		fprintf(ftime, "start_time: %.16f \n", start_sec);
		fprintf(ftime, "end_time:   %.16f \n", end_sec);
		fprintf(ftime, "total_duration:   %.16f \n", (end_sec - start_sec));
		fprintf(ftime, "writing_duration:   %.16f \n", (end_sec - start_logging_sec));

		fflush(ftime); // optional
		fclose(ftime);
	}

	return SUCCESS;
}
/* }}} */

/* {{{ PHP_MINFO_FUNCTION */
PHP_MINFO_FUNCTION(zimpaf)
{
	#if defined(ZTS) && defined(COMPILE_DL_TEST)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
	php_info_print_table_start();
	php_info_print_table_row(2, "zimpaf support", "enabled");
	php_info_print_table_row(2, "zimpaf version", PHP_ZIMPAF_VERSION);
	php_info_print_table_end();
}
/* }}} */
PHP_GINIT_FUNCTION(zimpaf){
#if defined(ZTS) && defined(COMPILE_DL_ZIMPAF)
	ZEND_TSRMLS_CACHE_UPDATE();
#endif
	zimpaf_globals->path_table = pemalloc(MAX_FILES * sizeof(char *), 1);
	memset(zimpaf_globals->path_table, 0, MAX_FILES * sizeof(char *));
	for (size_t i = 0; i < MAX_FILES; i++) {
        zimpaf_globals->path_table[i] = pemalloc(MAX_CHARS * sizeof(char), 1);
		memset(zimpaf_globals->path_table[i], 0, MAX_CHARS * sizeof(char));
    }
	zimpaf_globals->cur_filename = pemalloc(LENGTH_FNAME * sizeof(char), 1);
	memset(zimpaf_globals->cur_filename, 0, LENGTH_FNAME * sizeof(char));
	zimpaf_globals->path_table_size = MAX_FILES;
	zimpaf_globals->pt_cur_rows_size = MAX_CHARS;
	zimpaf_globals->coverage_id = NULL;
	zimpaf_globals->func_call_seq = NULL;
	zimpaf_globals->orig_bailout = NULL;

}

PHP_GSHUTDOWN_FUNCTION(zimpaf){
#if defined(ZTS) && defined(COMPILE_DL_ZIMPAF)
	ZEND_TSRMLS_CACHE_UPDATE();
#endif
	if (zimpaf_globals->path_table) {
        for (size_t i = 0; i < MAX_FILES; i++) {
            if (zimpaf_globals->path_table[i]) {
                pefree(zimpaf_globals->path_table[i], 1);
                zimpaf_globals->path_table[i] = NULL;
            }
        }
        pefree(zimpaf_globals->path_table, 1);
        zimpaf_globals->path_table = NULL;
    }

    if (zimpaf_globals->cur_filename) {
        pefree(zimpaf_globals->cur_filename, 1);
        zimpaf_globals->cur_filename = NULL;
    }
}

/* {{{ zimpaf_module_entry */
zend_module_entry zimpaf_module_entry = {
	STANDARD_MODULE_HEADER,
	"zimpaf",					/* Extension name */
	NULL,							/* zend_function_entry */
	PHP_MINIT(zimpaf),			/* PHP_MINIT - Module initialization */
	PHP_MSHUTDOWN(zimpaf),		/* PHP_MSHUTDOWN - Module shutdown */
	PHP_RINIT(zimpaf),			/* PHP_RINIT - Request initialization */
	PHP_RSHUTDOWN(zimpaf),		/* PHP_RSHUTDOWN - Request shutdown */
	PHP_MINFO(zimpaf),			/* PHP_MINFO - Module info */
	PHP_ZIMPAF_VERSION,			/* Version */
	PHP_MODULE_GLOBALS(zimpaf),	/* Globals, added manually after adding global var */ 
	PHP_GINIT(zimpaf),			/* PHP_GINIT - Module globals initialization */
	PHP_GSHUTDOWN(zimpaf),		/* PHP_GSHUTDOWN - Module globals shutdown */
	NULL,
	STANDARD_MODULE_PROPERTIES_EX
	// STANDARD_MODULE_PROPERTIES
};
/* }}} */

#ifdef COMPILE_DL_ZIMPAF
# ifdef ZTS
ZEND_TSRMLS_CACHE_DEFINE()
# endif
ZEND_GET_MODULE(zimpaf)
#endif

