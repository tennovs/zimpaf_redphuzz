# ZIMPAF — Zend Instrumentation Module for PHP Application Fuzzing

ZIMPAF is a Zend Engine instrumentation module designed to provide
high-fidelity runtime visibility for PHP applications during fuzzing.

It operates at the opcode level inside the Zend VM and enables precise
monitoring of execution behavior, control flow, data flow, and security-
relevant events.

## Research Context

ZIMPAF is developed as part of ongoing academic research on high-fidelity
runtime instrumentation and grey-box fuzzing for PHP applications at CSE Department,
College of Engineering, The University of Texas at Arlington.

---


## Design Goals

- High-fidelity instrumentation at Zend VM level
- Awareness of execution context
- Fine-grained coverage collection
- Security-oriented runtime tracing
- Integration with grey-box fuzzers (e.g., RedPhuzz)

---

## Instrumentation Capabilities

ZIMPAF hooks into Zend internals to collect:

### 1. Coverage Reports
- Opcode-level coverage
- Branch execution tracking

### 2. Error Monitoring
- `zend_error_cb` interception
- error_observer
- Robust errors reporting


### 3. Exception Monitoring
- `zend_throw_hook`
- `ZEND_THROW` opcode handler instrumentation
- Robust exceptions reporting

### 4. Function Call Tracing
- 75 Potentially vulnerable functions
- 36 Sanitization functions
- Security-sensitive language constructs: include, require, eval, exit, die
- 7 MySQL bind and execute functions

### 5. Input-to-Branch Comparisons
- Tracks branch instructions whose operands originate from user input
- Enables guided mutation strategies

### 6. MySQL Error Reporting
- mysqlnd and PDO driver access to get error number


### 7. Shell Error Reporting
- Reporting of errors for command injection functions.

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

ZIMPAF serves as the instrumentation backbone of ZIMPAF_RedPhuzz:

- Collects runtime feedback
- Feeds coverage information to the fuzzer
- Generates structured security reports
- Enables high-fidelity grey-box fuzzing for PHP applications

## Reporting Bugs

If you discover a bug or security issue, please open an issue on GitHub.
For responsible disclosure of security vulnerabilities, you may contact:

Tennov Simanjuntak
<tennov.simanjuntak@uta.edu>

---

## License

Copyright (c) 2026 Tennov Simanjuntak

All rights reserved.




