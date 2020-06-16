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
use Chevere\Components\Plugin\Plugs\Hooks\HooksRunner;
use Chevere\Components\Plugin\PlugsMapCache;
use Chevere\Components\Router\Resolver;
use Chevere\Components\Router\RouterCache;
use Chevere\Interfaces\Controller\ControllerInterface;
use Chevere\Interfaces\Plugin\Plugs\Hooks\PluggableHooksInterface;
use Ds\Map;
use Laminas\Diactoros\Response;
use Spiral\Goridge;
use Spiral\RoadRunner;

ini_set('display_errors', 'stderr');
require 'vendor/autoload.php'; // 8140 req/s

$cacheDir = new DirFromString(__DIR__ . '/cache/');
$cache = new Cache($cacheDir);
$routerCache = new RouterCache($cache->getChild('router/'));
$hooksCache = new PlugsMapCache($cache->getChild('plugs/hooks/'));
$resolver = new Resolver($routerCache);
$roadRunnerWorker = new RoadRunner\Worker(new Goridge\StreamRelay(STDIN, STDOUT));
$psr7 = new RoadRunner\PSR7Client($roadRunnerWorker);
$plugsQueueMap = new Map;
$routesMap = new Map;
while ($psrRequest = $psr7->acceptRequest()) {
    try {
        $uri = $psrRequest->getUri();
        $routed = $resolver->resolve($uri);
        $routeName = $routed->name()->toString();
        try {
            $route = $routesMap->get($routeName);
        } catch (OutOfBoundsException $e) {
            $route = $routerCache->routesCache()->get($routeName);
            $routesMap->put($routeName, $route);
        }
        $endpoint = $route->endpoints()->get($psrRequest->getMethod());
        $controller = $endpoint->controller();
        $controllerName = get_class($controller);
        try {
            $hooksQueue = $plugsQueueMap->get($controllerName);
        } catch (OutOfBoundsException $e) {
            $hooksQueue = $hooksCache->getPlugsQueueFor(get_class($controller));
            $plugsQueueMap->put($controllerName, $hooksQueue);
        }
        /**
         * @var PluggableHooksInterface $controller
         */
        $controller = $controller->withHooksRunner(
            new HooksRunner($hooksQueue)
        );
        /**
         * @var ControllerInterface $controller
         */
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

// ["Hello, rodolfo!!"]
