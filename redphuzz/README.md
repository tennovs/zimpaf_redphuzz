# RedPhuzz

RedPhuzz is a high-fidelity,  web application fuzzer that leverages runtime
semantics feedback produced by ZIMPAF, to achieve highly-targeted and deterministic fuzzing that goes beyond the traditional code coverage feedback. It also extends beyond simple error-and exception-based detection that fails when the code allows only well-formed input reaching vulnerable functions, resulting in no error while vulnerabilities still exist. Instead, it utilizes function-level semantics to detect these "silent vulnerabilities. The feedback includes:
- Coverage reports
- Error and Exception traces
- Function call and language construct execution traces
- Input-tainted branch traces .

---

## Core Novelties

### 1. Input- and Function-Level Fuzzing

RedPhuzz performs fuzzing at two complementary levels:

- **Function-level fuzzing** — Targets security-sensitive functions and language constructs based on runtime traces.
- **Input-level fuzzing** — Mutates HTTP parameters and payloads.

This dual-level mode increases precision when exploring vulnerability-relevant code paths.

---

## Mutation Strategies

For function-level fuzzing, RedPhuzz mutation specifically targets tainted-parameters and guided by three advanced mutation strategies to allow deterministic fuzzing.
### 1. Sanitization-Aware Mutation
Generates inputs that:
- Satisfy required sanitization sequences
- Bypass partial sanitization
- Reach vulnerable functions despite filtering

This enables deeper exploration of protected execution paths.

---

### 2. Input-tainted branch mutation
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

When function-level fuzzing completed, RedPhuzz continues with input-level fuzzing and perform stochastic/random mutation for path exploration.

---

### 4. Multi-Stage Vulnerability Detection

RedPhuzz uses multi-stage detection mechanisms:

#### (a) Error-Based Detection
Triggers when runtime errors or warnings are produced. Useful for detecting classic injection and misuse vulnerabilities.

#### (b) Function-Trace-Based Detection
Detects silent vulnerabilities that:
- Produce no errors
- Require well-formed input
- Only manifest through execution of dangerous functions

This allows detection beyond simple error and exception signals.

#### (c) Safe-Sequence Verification
Identifies whether sensitive functions are executed safely by verifying that they are preceded by a safe-call-semantics.
Example:
- `prepare`
- `bind`
- `execute`

Safe-sequence analysis is also used to validate function-based detection and reduce false positives.

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
- Reflected XSS is typically detected at the output level and may not directly correspond to a specific function or language construct.

---

## Reporting Bugs

If you discover a bug or security issue, please open an issue on GitHub. For responsible disclosure of security vulnerabilities, you may contact:

Tennov Simanjuntak
<tennov.simanjuntak@uta.edu>

---

## License

Copyright (c) 2026 Tennov Simanjuntak

All rights reserved.
