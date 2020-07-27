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
use Chevere\Components\Cache\CacheKey;
use Chevere\Components\Controller\ControllerArguments;
use Chevere\Components\Controller\ControllerRunner;
use Chevere\Components\Plugin\Plugs\Hooks\HooksRunner;
use Chevere\Components\Plugin\PlugsMapCache;
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
use function Chevere\Components\Filesystem\dirForString;

 // 13K req/s (php swoole)

require 'vendor/autoload.php';

$dir = dirForString(__DIR__ . '/');
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
$server->on('start', function (Server $server)
{
    echo "Swoole http server is started at http://127.0.0.1:9501\n";
});
$server->on('request', function (Request $request, Response $response) use (
    $plugsMapCache,
    $dispatcher,
    $psrFactory,
    $responseMerger,
    $plugsQueueMap
) {
    try {
        $psrRequest = new PsrRequest($request, $psrFactory, $psrFactory);
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
        $ran = $runner->execute($arguments);
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

// ["Hello, swoole!!"]
