# 01.ThrowableHandler

- [01.ThrowableHandler](#01throwablehandler)
  - [Description](#description)
  - [Examples](#examples)
    - [00.handle-plain.php](#00handle-plainphp)
    - [01.handle-console.php](#01handle-consolephp)
    - [02.handle-html.php](#02handle-htmlphp)
    - [03.errors-as-exception.php](#03errors-as-exceptionphp)
    - [04.exceptions-in-console.php](#04exceptions-in-consolephp)

## Description

Showcase on [exception handling](https://www.php.net/manual/en/language.exceptions.php) strategies.

## Examples

### [00.handle-plain.php](00.handle-plain.php)

A basic `try/catch` example. Once the exception gets caught, it is analyzed by the [exception handler]() which is used to generate a plain exception document.

> Output a plain text exception document

### [01.handle-console.php](01.handle-console.php)

Same as `00.handle-plain.php` but generating a colorized console output.

> Output a console exception output for `Whoops...`

### [02.handle-html.php](02.handle-html.php)

Same as `00.handle-plain.php` but generating two documents, for the same caught exception.

> Writes [02.handle-html.php-loud.html](02.handle-html.php-loud.html) and [02.handle-html.php-silent.html](02.handle-html.php-silent.html)

### [03.errors-as-exception.php](03.errors-as-exception.php)

An example using `\set_error_handler` for handling errors as exceptions.

> Output `Caught a nasty exception!`

### [04.exceptions-in-console.php](04.exceptions-in-console.php)

An example using `\set_exception_handler` for handling exceptions in the console.

> Output a console exception document