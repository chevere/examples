# 02.VarDump

- [02.VarDump](#02vardump)
  - [Description](#description)
  - [Examples](#examples)
    - [00.vardump.php](#00vardumpphp)
    - [01.xdd-plain.php](#01xdd-plainphp)
    - [02.xdd-console.php](#02xdd-consolephp)
    - [03.xdd-html.php](#03xdd-htmlphp)

## Description

Showcase usage of [VarDump]() component.

## Examples

### [00.vardump.php](00.vardump.php)

Basic construction of a VarDump object, it pass variables that will be dumped to the output stream.

> Output a console VarDump output

### [01.xdd-plain.php](01.xdd-plain.php)

Uses [VarDumpInstance]() to set a system-wide plain text instance, used by functions `xd` and `xdd`.

> Runs `xd` (screen dump) and `xdd` (screen dump and die)

### [02.xdd-console.php](02.xdd-console.php)

Same as `01.xdd-console.php` but for console var dump.

### [03.xdd-html.php](03.xdd-html.php)

Same as `01.xdd-console.php` but for HTML var dump.