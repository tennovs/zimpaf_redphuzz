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


#ifndef ZIMPAP_FPM_REQUEST_RETRIEVAL_H
#define ZIMPAP_FPM_REQUEST_RETRIEVAL_H

#include "php.h"

char* get_http_header(const char *header_name);

#endif /* ZIMPAP_FPM_REQUEST_RETRIEVAL_H */
