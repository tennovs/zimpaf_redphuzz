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


#ifndef DSERIALIZER_HOOK_H
#define DSERIALIZER_HOOK_H

#include "php.h"

void hook_unserialize();
void hook_yaml_parse();
void hook_yaml_parse_file();
void hook_unpack();
void hook_igbinary_unserialize();

#endif
