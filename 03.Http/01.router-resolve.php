<?php

/*
 * This file is part of Chevere.
 *
 * (c) Rodolfo Berrios <rodolfo@chevere.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

use Chevere\Components\Cache\Cache;
use Chevere\Components\Controller\ControllerArguments;
use Chevere\Components\Controller\ControllerRunner;
use Chevere\Components\Filesystem\DirFromString;
use Chevere\Components\Instances\VarDumpInstance;
use Chevere\Components\Router\Resolver;
use Chevere\Components\Router\RouterCache;
use Chevere\Components\VarDump\VarDumpMake;
use Chevere\Components\Writers\StreamWriterFromString;
use Laminas\Diactoros\Uri;

foreach (['../vendor/autoload.php', 'vendor/autoload.php'] as $autoload) {
    if (stream_resolve_include_path($autoload)) {
        require $autoload; // 241 req/s (Internal)
        break;
    }
}

$writer = new StreamWriterFromString('php://stdout', 'w');
new VarDumpInstance(
    VarDumpMake::console($writer)
);
$uri = new Uri('/hello-rodolfo/');
$cacheDir = new DirFromString(__DIR__ . '/cache/router/');
$cache = new RouterCache(new Cache($cacheDir));
$resolver = new Resolver($cache);
$routed  = $resolver->resolve($uri);
$routeName = $routed->name()->toString();
$route = $cache->routesCache()->get($routeName);
$endpoint = $route->endpoints()->get('GET');
$controller = $endpoint->controller();
$arguments = new ControllerArguments(
    $controller->parameters(),
    $routed->arguments()
);
$runner = new ControllerRunner($controller);
$ran = $runner->ran($arguments);
echo $uri->__toString() . ' >>> ' . implode(' ', $ran->data());

// /hello-rodolfo/ >>> Hello, rodolfo
