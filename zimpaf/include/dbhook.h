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


#ifndef DB_HOOK_H
#define DB_HOOK_H

#include "php.h"

void hook_mysqli_query();
void hook_mysqli_query_cm();
void hook_pdo_query_cm();
void hook_mysqli_real_query();
void hook_mysqli_real_query_cm();
void hook_mysqli_multi_query();
void hook_mysqli_multi_query_cm();
void hook_mysqli_prepare();
void hook_mysqli_prepare_cm();
void hook_pdo_prepare_cm();
void hook_pdo_exec_cm();
void hook_mysqli_stmt_bind_param();
void hook_mysqli_stmt_bind_param_cm();
void hook_pdostmt_bindParam_cm();
void hook_pdostmt_bindValue_cm();
void hook_mysqli_execute_query();
void hook_mysqli_execute_query_cm();
void hook_mysqli_stmt_execute();
void hook_mysqli_stmt_execute_cm();
void hook_pdostmt_execute_cm();

#endif
