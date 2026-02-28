# RedPhuzz

RedPhuzz is a high-fidelity web application fuzzer that leverages runtime
information produced by ZIMPAF, including:

- Coverage reports
- Error reports
- Exception traces
- Language construct traces
- Function call traces
- Input parameters used as operands in branch instruction.

By combining grey-box feedback with fine-grained Zend-level instrumentation,
RedPhuzz enables precise and structured vulnerability detection for PHP
applications.

---

## Core Novelties

### 1. Input- and Function-Level Fuzzing

RedPhuzz performs fuzzing at two complementary levels:

- **Input-level fuzzing** — Mutates HTTP parameters and payloads.
- **Function-level fuzzing** — Targets security-sensitive functions and
  language constructs based on runtime traces.

This dual-level strategy increases precision when exploring vulnerability-relevant code paths.

---

### 2. Multi-Stage Vulnerability Detection

RedPhuzz uses layered detection mechanisms:

#### (a) Error-Based Detection
Triggers when runtime errors or warnings are produced.
Useful for detecting classic injection and misuse vulnerabilities.

#### (b) Function-Trace-Based Detection
Detects silent vulnerabilities that:
- Produce no errors
- Require well-formed input
- Only manifest through execution of dangerous functions

This allows detection beyond simple crash or error signals.

#### (c) Safe-Sequence Verification
Identifies whether sensitive functions are executed safely by verifying
that they are preceded by a correct sequence of sanitization or secure calls.

Example secure sequence:
- `prepare`
- `bind`
- `execute`

Safe-sequence analysis is also used to validate function-based detection
and reduce false positives.

---

## Mutation Strategies

RedPhuzz implements three advanced mutation strategies:

### 1. Sanitization-Aware Mutation
Generates inputs that:
- Satisfy required sanitization sequences
- Bypass partial sanitization
- Reach vulnerable functions despite filtering

This enables deeper exploration of protected execution paths.

---

### 2. Parameter-in-Branch Mutation
Targets branch instructions whose operands originate from user input.

Two sub-strategies:

- **Preserving-condition mutation**
  - Keeps mutations that still reach vulnerability-relevant functions.
  - Maintains path constraints while refining payloads.

- **Flipping-condition mutation**
  - Actively flips branch outcomes.
  - Explores alternative execution paths.

---

### 3. Data-Type-Aware Mutation
Generates mutations that:
- Comply with expected database constraints
- Deliberately violate type or schema expectations
- Respect or break format assumptions

This is particularly effective for database-driven applications.

---

## Supported Vulnerability Classes

RedPhuzz targets six classes of vulnerabilities related to
security-sensitive functions and language constructs:

1. Code Injection
2. Path Traversal
3. SQL Injection (SQLi)
4. Unserialize Vulnerabilities
5. XML External Entity (XXE)
6. Cross-Site Scripting (XSS)

Notes:

- Stored XSS often involves SQL `INSERT` / `UPDATE` flows.
- Reflected XSS is typically detected at the output level and may not
  directly correspond to a specific function or language construct.

---

## Design Philosophy

RedPhuzz is designed to:

- Go beyond simple error-based fuzzing
- Detect silent vulnerabilities
- Reduce false positives via safe-sequence verification
- Use structured runtime feedback instead of blind mutation
- Integrate tightly with Zend-level instrumentation

It is built for high-fidelity, research-grade fuzzing of PHP web applications.

## Reporting Bugs

If you discover a bug or security issue, please open an issue on GitHub.
For responsible disclosure of security vulnerabilities, you may contact:

Tennov Simanjuntak
<tennov.simanjuntak@uta.edu>

---

## License

Copyright (c) 2026 Tennov Simanjuntak

All rights reserved.
