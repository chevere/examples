# 03.Http

- [03.Http](#03http)
  - [Description](#description)
  - [Examples](#examples)
    - [00.router-make.php](#00router-makephp)
    - [01.router-resolve.php](#01router-resolvephp)
    - [02.router-resolve-roadrunner.php](#02router-resolve-roadrunnerphp)
    - [03.router-resolve-swoole.php](#03router-resolve-swoolephp)
  - [Notes](#notes)

## Description

Examples on HTTP resolving. The route is at [`routes`](routes) and cache is stored at [`cache`](cache).

## Examples

### [00.router-make.php](00.router-make.php)

This file is used to generate both cached routing and plugin mapping, which are used in akk examples of this chapter.

```shell
php 03.Http/00.router-make.php 
```

> Generates router cache at [`cache/router/`](cache/router/) and hooks plugs map cache at [`cache/plugs/hooks/`](cache/plugs/hooks/)

### [01.router-resolve.php](01.router-resolve.php)

```shell
php 03.Http/01.router-resolve.php
```

Resolves cached routing for the `/hello-chevere` uri, applying hooks.

Run it using the built-in server:

```shell
php -S 127.0.0.1:8888 -t 03.Http/ 
```

Requests `http://127.0.0.1:8888/01.router-resolve.php`

> Outputs ["greet" => "Hello, chevere!!"]

### [02.router-resolve-roadrunner.php](02.router-resolve-roadrunner.php)

Same as `01.router-resolve.php` but under [RoadRunner](https://roadrunner.dev/).

You will need to build the binary running the following command:

```shell
./vendor/bin/rr get
```

Run the RoadRunner server using `rr`:

```shell
./rr serve
```

> The configuration can be found at [`.rr.yaml`](../.rr.yaml)

Requests `http://127.0.0.1:8080/hello-roadrunner`

```shell
curl http://127.0.0.1:8080/hello-roadrunner
```

> Outputs ["greet" => "Hello, roadrunner!!"]

### [03.router-resolve-swoole.php](03.router-resolve-swoole.php)

Same as `01.router-resolve.php` but under [Swoole](https://www.swoole.co.uk/). You need to run the following command to install Swoole:

```shell
sudo pecl install swoole
```

A cache mapping strategy is used on `$plugsQueueMap` and `$routesMap`. Run the Swoole server using:

```shell
php 03.Http/03.router-resolve-swoole.php
```

Requests `http://127.0.0.1:9501/hello-swoole`

```shell
curl http://127.0.0.1:9501/hello-swoole
```

> Outputs ["greet" => "Hello, swoole!!"]

## Notes

> 👍🏾 A cache strategy is used with `$plugsQueueMap` and `$routesMap` for [`02.router-resolve-roadrunner.php`](#02router-resolve-roadrunnerphp) and [`03.router-resolve-swoole.php`](#03router-resolve-swoolephp) to re-use expensive objects within the process runner.
