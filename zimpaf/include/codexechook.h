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


#ifndef CODEXEC_HOOK_H
#define CODEXEC_HOOK_H

#include "php.h"

void hook_assert();
void hook_system();
void hook_exec();
void hook_passthru();
void hook_shell_exec();
void hook_popen();
void hook_proc_open();

#endif
