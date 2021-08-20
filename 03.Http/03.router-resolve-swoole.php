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
use Chevere\Components\ThrowableHandler\Documents\ThrowableHandlerHtmlDocument;
use Chevere\Components\ThrowableHandler\ThrowableHandler;
use Chevere\Components\ThrowableHandler\ThrowableRead;
use Chevere\Exceptions\Router\RouteNotFoundException;
use Chevere\Interfaces\Controller\ControllerInterface;
use Ds\Map;
use Imefisto\PsrSwoole\Request as PsrRequest;
use Imefisto\PsrSwoole\ResponseMerger;
use Nyholm\Psr7\Factory\Psr17Factory;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;
use function Chevere\Components\Filesystem\dirForPath;

// 12,000 req/s

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
$server = new Server('127.0.0.1', 9501);
$psrFactory = new Psr17Factory;
$responseMerger = new ResponseMerger;
$plugsQueueMap = new Map;
$routesMap = new Map;
$controllers = new Map;
$runners = new Map;
$server->on('start', function (Server $server)
{
    echo "Swoole http server is started at http://127.0.0.1:9501\n";
});
$server->on('request', function (Request $request, Response $response) use (
    $plugsMapCache,
    $dispatcher,
    $psrFactory,
    $responseMerger,
    $plugsQueueMap,
    $controllers,
    $runners
) {
    try {
        $psrRequest = new PsrRequest($request, $psrFactory, $psrFactory);
        $uri = $psrRequest->getUri();
        $routed = $dispatcher->dispatch($psrRequest->getMethod(), urldecode($uri->getPath()));
        $controllerName = $routed->controllerName()->toString();
        /** @var ControllerInterface $controller */
        try {
            $controller = $controllers->get($controllerName);
        } catch(OutOfBoundsException $e) {
            $controller = new $controllerName;
            $controllers->put($controllerName, $controller);
        }
        try {
            $hooksQueue = $plugsQueueMap->get($controllerName);
        } catch (OutOfBoundsException $e) {
            $hooksQueue = $plugsMapCache->getPlugsQueueTypedFor($controllerName);
            $plugsQueueMap->put($controllerName, $hooksQueue);
        }
        $controller = $controller->withHooksRunner(
            new HooksRunner($hooksQueue)
        );
        try {
            $runner = $runners->get($controllerName);
        } catch (OutOfBoundsException $e) {
            $runner = new ActionRunner($controller);
            $runners->put($controllerName, $runner);
        }
        $ran = $runner->execute(...$routed->arguments());
        $psrResponse = $psrFactory->createResponse();
        $psrResponse->getBody()->write(json_encode($ran->data()));
        $response->header('Content-Type', 'text/plain');
        $responseMerger->toSwoole($psrResponse, $response)->end();
    } catch (RouteNotFoundException $e) {
        $response->status(404);
        $response->end();
    } catch (\Exception $e) {
        $response->header('Content-Type', 'text/html');
        $handler = new ThrowableHandler(new ThrowableRead($e));
        $document = new ThrowableHandlerHtmlDocument($handler);
        $response->status(500);
        $response->end($document->toString());
    }
});
$server->start();

// ["greet" => "Hello, swoole!!"]
