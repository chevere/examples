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
use Chevere\Components\ExceptionHandler\Documents\HtmlDocument;
use Chevere\Components\ExceptionHandler\ExceptionHandler;
use Chevere\Components\ExceptionHandler\ExceptionRead;
use Chevere\Components\Filesystem\DirFromString;
use Chevere\Components\Plugin\Plugs\Hooks\HooksQueue;
use Chevere\Components\Plugin\Plugs\Hooks\HooksRunner;
use Chevere\Components\Router\Resolver;
use Chevere\Components\Router\RouterCache;
use Chevere\Examples\HelloWorld\HelloWorldHookHook;
use Chevere\Exceptions\Router\RouteNotFoundException;
use Chevere\Interfaces\Controller\ControllerInterface;
use Imefisto\PsrSwoole\Request as PsrRequest;
use Imefisto\PsrSwoole\ResponseMerger;
use Nyholm\Psr7\Factory\Psr17Factory;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

require 'vendor/autoload.php'; // 11544 req/s
$cacheDir = new DirFromString(__DIR__ . '/cache/router/');
$cache = new Cache($cacheDir);
$routerCache = new RouterCache($cache);
$resolver = new Resolver($routerCache);
$server = new Server('127.0.0.1', 9501);
$psrFactory = new Psr17Factory;
$responseMerger = new ResponseMerger;
$server->on('start', function (Server $server)
{
    echo "Swoole http server is started at http://127.0.0.1:9501\n";
});
$server->on('request', function (Request $request, Response $response) use (
    $routerCache,
    $resolver,
    $psrFactory,
    $responseMerger
) {
    try {
        $psrRequest = new PsrRequest($request, $psrFactory, $psrFactory);
        $uri = $psrRequest->getUri();
        $routed = $resolver->resolve($uri);
        $routeName = $routed->name()->toString();
        $route = $routerCache->routesCache()->get($routeName);
        $endpoint = $route->endpoints()->get($psrRequest->getMethod());
        $controller = $endpoint->controller();
        // $controller = $controller->withHooksRunner(
        //     new HooksRunner(
        //         (new HooksQueue)
        //             ->withAddedHook(new HelloWorldHookHook)
        //     )
        // );
        // /**
        //  * @var ControllerInterface $controller
        //  */
        $runner = new ControllerRunner($controller);
        $arguments = new ControllerArguments(
            $controller->parameters(),
            $routed->arguments()
        );
        $ran = $runner->ran($arguments);
        $psrResponse = $psrFactory->createResponse();
        $psrResponse->getBody()->write(json_encode($ran->data()));
        $response->header('Content-Type', 'text/plain');
        $responseMerger->toSwoole($psrResponse, $response)->end();
    } catch (RouteNotFoundException $e) {
        $response->status(404);
        $response->end();
    } catch (\Exception $e) {
        $response->header('Content-Type', 'text/html');
        $handler = new ExceptionHandler(new ExceptionRead($e));
        $document = new HtmlDocument($handler);
        $response->status(500);
        $response->end($document->toString());
    }
});
$server->start();

// ["Hello, rodolfo"]
