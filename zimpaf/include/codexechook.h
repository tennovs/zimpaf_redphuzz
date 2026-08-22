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