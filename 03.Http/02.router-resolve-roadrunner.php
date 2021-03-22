<?php

use Spiral\RoadRunner;
use Nyholm\Psr7;
use Chevere\Components\Action\ActionRunner;
use Chevere\Components\Cache\Cache;
use Chevere\Components\Cache\CacheKey;
use Chevere\Components\Pluggable\Plug\Hook\HooksRunner;
use Chevere\Components\Pluggable\PlugsMapCache;
use Chevere\Components\Router\RouterDispatcher;
use Ds\Map;
use function Chevere\Components\Filesystem\dirForPath;

include "vendor/autoload.php";

$dir = dirForPath(__DIR__ . '/');
$cacheDir = $dir->getChild('cache/');
$routeCollector = (new Cache($cacheDir->getChild('router/')))
    ->get(new CacheKey('my-route-collector'))
    ->var();
$dispatcher = new RouterDispatcher($routeCollector);
$plugsMapCache = new PlugsMapCache(
    new Cache($cacheDir->getChild('plugs/hooks/'))
);
$plugsQueueMap = new Map;
$routesMap = new Map;

$worker = RoadRunner\Worker::create();
$psrFactory = new Psr7\Factory\Psr17Factory();

$worker = new RoadRunner\Http\PSR7Worker($worker, $psrFactory, $psrFactory, $psrFactory);

while ($request = $worker->waitRequest()) {
    try {
        $uri = $request->getUri();
        $routed = $dispatcher->dispatch($request->getMethod(), urldecode($uri->getPath()));
        $controllerName = $routed->controllerName()->toString();
        // $controller = new $controllerName;
        // try {
        //     $hooksQueue = $plugsQueueMap->get($controllerName);
        // } catch (OutOfBoundsException $e) {
        //     $hooksQueue = $plugsMapCache->getPlugsQueueTypedFor($controllerName);
        //     $plugsQueueMap->put($controllerName, $hooksQueue);
        // }
        // $controller = $controller->withHooksRunner(
        //     new HooksRunner($hooksQueue)
        // );
        // $runner = new ActionRunner($controller);
        // $ran = $runner->execute(...$routed->arguments());
        $response = new Psr7\Response();
        // $response->getBody()->write(json_encode($ran->data()));
        $response->getBody()->write('Hello, RR!');
        $worker->respond($response);
    } catch (Throwable $e) {
        // $worker->getWorker()->error((string)$e);
    }
}