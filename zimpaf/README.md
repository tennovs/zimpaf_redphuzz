
## Releases

### v1.2.0 (2026-08-24)
Add supports for PHP-8.4 and 8.5 in addition to the existing 8.3. Tested with PHP 8.3.19, 8.4.24, 8.5.7, and 8.5.9.
- Adds a ZVAL structure fallback for the purged `zval_dtor`.
- Adds function interceptor for `exit` and `die` (See the `generic_lang_construct_handler` function in `zimpaf.c` and `flowfunchook.c` in libhooks folder).
- Puts bailout codes inside conditional inclusion because bailout works for PHP < 8.4.
- Adds `hooks_installer.c` and move function hooks from `zimpaf.c` into it.
- Adds ZIMPAF owned directory creation, to decouple it from RedPhuzz.
- Adds directory support for Windows (the plan is to add support for Windows), but this has not been tested.


### v1.1.0 (2026-04-13)
#### zimpaf.c
- Add ZEND_JMP_SET and ZEND_COALESCE hooks
- Modify GINIT and GSHUTDOWN to use pointer-style access ("->") in global module variable access so that ZIMPAF can be used in both ZTS and Non-ZTS

#### utils.h
- Add function declaration: get_zval_ptr
- Update function declaration: is_zval_in_superglobal and is_zval_value_in_array

#### utils.c
- Update is_zval_in_superglobal
- Fixed logic issues in is_zval_value_in_array and optimized its implementation, achieving ~40–50% memory reduction in large PHP applications, such large WordPress plugins

# ZIMPAF — Zend Instrumentation Module for PHP Application Fuzzing

ZIMPAF is a novel instrumentation module for the Zend interpreter, designed to provide
high-fidelity runtime visibility for PHP application fuzzing tho allow fuzzer that goes beyond coverage-feedback and errors and exceptions. The fuzzer operates on dual execution mode: deterministic when runtime semantics available to uncover vulnerabilities/bugs and stochastic/random for path exploration.

It performs fine-grained and targeted interception within the Zend VM to achieve efficient instrumentation, avoiding global interception at the Zend Engine’s main execution loop (zend_execute_ex) and the function call dispatcher (e.g., ZEND_DO_FCALL). It monitors execution behavior by intercepting branch instructions, security-sensitive language constructs, function calls, as well as errors and exceptions.

## Research Context

ZIMPAF is developed as part of ongoing academic research on high-fidelity
runtime instrumentation and grey-box fuzzing for PHP applications at CSE Department,
College of Engineering, The University of Texas at Arlington.

---


## Design Goals
To provide a novel, efficient, and effective instrumentation that avoids code patching, usable, extendable, and adapts to the evolution of the PHP.

- High-fidelity instrumentation at Zend VM level
- Awareness of execution context
- Fine-grained coverage collection via branch instructions
- Fine-grained tracing of language constructs and function calls
- Security-oriented runtime tracing
- Integration with grey-box fuzzers (e.g., RedPhuzz)

---

## Instrumentation Capabilities

ZIMPAF hooks into Zend internals to collect:

### 1. Coverage Reports
- Opcode-level coverage
- Branch execution tracking

### 2. Error Monitoring
Robust errors reporting via:
- Main error handler interception: `zend_error_cb`
- error_observer
- Fetch database error from mysqlnd and PDO driver

### 3. MySQL Error Reporting
- mysqlnd and PDO driver access to get error number

### 4. Shell Error Reporting
- Reporting of errors for command injection functions from within the interpreter, avoiding external execution.
- When return value cannot be used to infer error, simulate command injection to retrieve error from resource stream (also see 6 below)


### 5. Exception Monitoring
Robust exceptions reporting via:
- Main execeptions handler interception:`zend_throw_exception_hook`
- `ZEND_THROW` opcode handler instrumentation

### 6. Function Call Tracing
- 75 Potentially vulnerable functions
- 36 Sanitization functions
- Security-sensitive language constructs: include, require, eval, exit, die
- 7 MySQL bind and execute functions
- Allow highly-targeted and deterministic mutation guided by tainted function and language construct parameters, and sanitization. Sanitization-aware mutation allows reaching vulnerable functions hidden behind sanitization. 
- Allow detetion of 'silent vulnerability' where input transformations, including sanitizations allow only well-formed input to reach vulnerable functions resulting no error, while vulnerabilities remain.
- Simulate command injection safely and efficiently inside the `Zend` interpreter to retrieve error (see 4 above)

### 7. Input-tainted branch tracking
- Efficiently tracks branch instructions whose operands originate from user input by matching the pointer of branch operands with superglobal array member. Traverse the array and match string, as a fallback.
- Allow deterministic mutation to reach vulnerable functions hidden behind branches.

---

## Log Directories (Inside Container)

```
/shared-tmpfs/coverage-reports
/shared-tmpfs/error-reports
/shared-tmpfs/exception-reports
/shared-tmpfs/function-call-traces
/shared-tmpfs/input_params_comparisons
/shared-tmpfs/mysql-error-reports
/shared-tmpfs/shell-error-reports
```

Although the directory names include `tmpfs`, they are not memory-backed.
Logs are stored on disk to avoid excessive memory usage during long fuzzing runs.

---

## Architecture Role in ZIMPAF_RedPhuzz

ZIMPAF is the enabler or the instrumentation backbone for high-fidelity, highly-targeted, and deterministic fuzzer, e.g. RedPhuzz:
- Collects runtime feedback containing the traces of function calls, language constructs, input-tainted branches, coverage, and errors and exceptions.
- Feeds feedback to the fuzzer to enables high-fidelity grey-box fuzzing for PHP applications

## Reporting Bugs

If you discover a bug or security issue, please open an issue on GitHub.
For responsible disclosure of security vulnerabilities, you may contact:

Tennov Simanjuntak
<tennov.simanjuntak@uta.edu>

---

## License

Copyright (c) 2026 Tennov Simanjuntak

All rights reserved.




