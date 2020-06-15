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
use Chevere\Components\Router\Resolver;
use Chevere\Components\Router\RouterCache;
use Laminas\Diactoros\Response;
use Spiral\Goridge;
use Spiral\RoadRunner;

ini_set('display_errors', 'stderr');
require 'vendor/autoload.php'; // 8140 req/s

$cacheDir = new DirFromString(__DIR__ . '/cache/router/');
$cache = new RouterCache(new Cache($cacheDir));
$resolver = new Resolver($cache);
$roadRunnerWorker = new RoadRunner\Worker(new Goridge\StreamRelay(STDIN, STDOUT));
$psr7 = new RoadRunner\PSR7Client($roadRunnerWorker);
while ($serverRequest = $psr7->acceptRequest()) {
    try {
        $uri = $serverRequest->getUri();
        $routed  = $resolver->resolve($uri);
        $routeName = $routed->name()->toString();
        $route = $cache->routesCache()->get($routeName);
        $endpoint = $route->endpoints()->get($serverRequest->getMethod());
        $controller = $endpoint->controller();
        $runner = new ControllerRunner($controller);
        $arguments = new ControllerArguments(
            $controller->parameters(),
            $routed->arguments()
        );
        $ran = $runner->ran($arguments);
        $response = new Response;
        $response->getBody()->write(json_encode($ran->data()));
        $psr7->respond($response);
    } catch (\Throwable $e) {
        $psr7->getWorker()->error($e->__toString());
    }
}

// ["Hello, rodolfo"]
