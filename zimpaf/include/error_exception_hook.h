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


#ifndef ERROR_EXCEPTION_HOOK_H
#define ERROR_EXCEPTION_HOOK_H

#include "php.h"

//main error mechanism via zend_error_cb and zend_throws_exception hook
void hook_zend_error_cb();
void hook_zend_throw_exception_hook();

//to log error when main error mechanism is bypassed by zend interpreter, e.g. e-warning and e-notice
void zimpaf_observer_error_handler(int type, zend_string *error_filename, uint32_t error_lineno, 
                                     zend_string *message);

//to log error when main exception mechanism is bypassed by zend interpreter, e.g. user exception handler is set.                                      
void zimpaf_observe_exception(zend_object *ex);

#endif


