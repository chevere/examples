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

## [00.vardump.php](00.vardump.php)

Uses the function `Chevere\Components\VarDump\getVarDumpPlain` to quickly build a plain text var dump instance.

> Output `a var` plain text var dump output

## [01.xdd-plain.php](01.xdd-plain.php)

Uses [VarDumpInstance]() to set a system-wide plain text instance, used by functions `xd` and `xdd`.

> Runs `xd` (screen dump) and `xdd` (screen dump and die)

## [02.xdd-console.php](02.xdd-console.php)

Same as `01.xdd-console.php` but for console var dump.

## [03.xdd-html.php](03.xdd-html.php)

Same as `01.xdd-console.php` but for HTML var dump.