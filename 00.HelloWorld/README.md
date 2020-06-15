# 00.HelloWorld

- [00.HelloWorld](#00helloworld)
  - [Description](#description)
  - [Examples](#examples)
    - [00.controller.php](#00controllerphp)
    - [01.writer.php](#01writerphp)
    - [02.hook.php](#02hookphp)
    - [03.event.php](#03eventphp)

## Description

The ubiquitous `Hello, World!` example.

## Examples

### [00.controller.php](00.controller.php)

Runs [`HelloWorldController`](../src/HelloWorld/HelloWorldController.php) [controller](https://github.com/chevere/docs/blob/master/components/controller.md).

> Hello, World

### [01.writer.php](01.writer.php)

Same as `00.controller.php` but writing the output to [`01.writer.php.log`](01.writer.php.log) by using a [stream writer]().

### [02.hook.php](02.hook.php)

Same as `00.controller.php` but applying [`HelloWorldHookHook`](../src/HelloWorld/HelloWorldHookHook.php) [hook](https://github.com/chevere/docs/blob/master/components/plugin.md#pluggable-hooks).

> Hello, World!!

### [03.event.php](03.event.php)

Same as `00.controller.php` but applying [`HelloWorldEvent`](../src/HelloWorld/HelloWorldEvent.php) [event](https://github.com/chevere/docs/blob/master/components/plugin.md#pluggable-events).

