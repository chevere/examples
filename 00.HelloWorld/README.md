# 00.HelloWorld

- [00.HelloWorld](#00helloworld)
  - [Description](#description)
  - [Examples](#examples)
    - [00.controller.php](#00controllerphp)
    - [01.hook.php](#01hookphp)
    - [02.event.php](#02eventphp)
    - [03.writer.php](#03writerphp)

## Description

The ubiquitous `Hello, World!` example.

## Examples

### [00.controller.php](00.controller.php)

Runs [`HelloWorldController`](../src/HelloWorld/HelloWorldController.php) [controller](https://github.com/chevere/docs/blob/master/components/controller.md).

> Hello, World

### [01.hook.php](01.hook.php)

Same as `00.controller.php` but applying [`HelloWorldHookHook`](../src/HelloWorld/HelloWorldHookHook.php) [hook](https://github.com/chevere/docs/blob/master/components/plugin.md#pluggable-hooks).

> Hello, World!!

### [02.event.php](02.event.php)

Same as `00.controller.php` but applying [`HelloWorldEvent`](../src/HelloWorld/HelloWorldEvent.php) [event](https://github.com/chevere/docs/blob/master/components/plugin.md#pluggable-events).

### [03.writer.php](03.writer.php)

Same as `00.controller.php` but writing the output to [`03.writer.php.log`](03.writer.php.log) by using a [StreamWriter]().