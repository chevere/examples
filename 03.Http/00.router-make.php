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
use Chevere\Components\ExceptionHandler\Documents\ConsoleDocument;
use Chevere\Components\ExceptionHandler\ExceptionHandler;
use Chevere\Components\ExceptionHandler\ExceptionRead;
use Chevere\Components\ExceptionHandler\Handle;
use Chevere\Components\Filesystem\DirFromString;
use Chevere\Components\Instances\RuntimeInstance;
use Chevere\Components\Instances\VarDumpInstance;
use Chevere\Components\Instances\WritersInstance;
use Chevere\Components\Plugin\PlugsMapper;
use Chevere\Components\Plugin\PlugsQueue;
use Chevere\Components\Plugin\Types\HookPlugType;
use Chevere\Components\Plugs\Hooks\HooksQueue;
use Chevere\Components\Router\Resolver;
use Chevere\Components\Router\RouterCache;
use Chevere\Components\Router\RouterMaker;
use Chevere\Components\Routing\FsRoutesMaker;
use Chevere\Components\Routing\Routing;
use Chevere\Components\Runtime\Runtime;
use Chevere\Components\Runtime\Sets\SetErrorHandler;
use Chevere\Components\Runtime\Sets\SetExceptionHandler;
use Chevere\Components\VarDump\VarDumpMake;
use Chevere\Components\Writers\StreamWriterFromString;
use Chevere\Components\Writers\Writers;
use Chevere\Examples\HelloWorld\HookHelloWorldController;

require 'vendor/autoload.php';

// new WritersInstance(new Writers);
// new VarDumpInstance(
//     VarDumpMake::console((new Writers)->out())
// );
$routesDir = new DirFromString(__DIR__ . '/routes/');
$routing = new Routing(
    new FsRoutesMaker($routesDir),
    new RouterMaker
);
$cacheDir = new DirFromString(__DIR__ . '/cache/router/');
$cache = new Cache($cacheDir);
$routerCache = new RouterCache($cache);
$resolver = new Resolver($routerCache);
$routerCache->put($routing->router());

// try {
//     $hooksDir = new DirFromString(dirname(__DIR__) . '/src/');
//     $hooksMapper = new PlugsMapper($hooksDir, new HookPlugType);
//     xdd($hooksMapper->plugsMap()->hasPlugsFor(HookHelloWorldController::class));
//     xdd($hooksMapper->plugsMap()->getPlugsFor(HookHelloWorldController::class));
//     foreach ($hooksMapper->plugsMap()->getGenerator() as $controller => $plugsQueue) {
//         xdd($controller, $plugsQueue);
//     }
// } catch (Exception $e) {
//     $handler = new ExceptionHandler(new ExceptionRead($e));
//     $document = new ConsoleDocument($handler);
//     echo $document->toString() . "\n";
// }

// xdd($hooksDir->path()->absolute());
