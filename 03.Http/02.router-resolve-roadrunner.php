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

use Chevere\Components\Action\ActionRunner;
use Chevere\Components\Cache\Cache;
use Chevere\Components\Cache\CacheKey;
use Chevere\Components\Pluggable\Plug\Hook\HooksRunner;
use Chevere\Components\Pluggable\PlugsMapCache;
use Chevere\Components\Router\RouterDispatcher;
use Ds\Map;
use Laminas\Diactoros\Response;
use Spiral\Goridge;
use Spiral\RoadRunner;
use function Chevere\Components\Filesystem\dirForPath;

// 9,500 req/s

ini_set('display_errors', 'stderr');
require 'vendor/autoload.php';

$dir = dirForPath(__DIR__ . '/');
$cacheDir = $dir->getChild('cache/');
$routeCollector = (new Cache($cacheDir->getChild('router/')))
    ->get(new CacheKey('my-route-collector'))
    ->var();
$dispatcher = new RouterDispatcher($routeCollector);
// Hooks caching
$plugsMapCache = new PlugsMapCache(
    new Cache($cacheDir->getChild('plugs/hooks/'))
);
$roadRunnerWorker = new RoadRunner\Worker(new Goridge\StreamRelay(STDIN, STDOUT));
$psr7 = new RoadRunner\PSR7Client($roadRunnerWorker);
$plugsQueueMap = new Map;
$routesMap = new Map;
while ($psrRequest = $psr7->acceptRequest()) {
    try {
        $uri = $psrRequest->getUri();
        $routed = $dispatcher->dispatch($psrRequest->getMethod(), $uri->getPath());
        $controllerName = $routed->controllerName()->toString();
        $controller = new $controllerName;
        try {
            $hooksQueue = $plugsQueueMap->get($controllerName);
        } catch (OutOfBoundsException $e) {
            $hooksQueue = $plugsMapCache->getPlugsQueueTypedFor($controllerName);
            $plugsQueueMap->put($controllerName, $hooksQueue);
        }
        $controller = $controller->withHooksRunner(
            new HooksRunner($hooksQueue)
        );
        $runner = new ActionRunner($controller);
        $ran = $runner->execute(...$routed->arguments());
        $response = new Response;
        $response->getBody()->write(json_encode($ran->data()));
        $psr7->respond($response);
    } catch (\Throwable $e) {
        $psr7->getWorker()->error($e->__toString());
    }
}

// ["greet" => "Hello, roadrunner!!"]
