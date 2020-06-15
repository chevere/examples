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
use Chevere\Components\Plugin\Plugs\Hooks\HooksQueue;
use Chevere\Components\Router\Resolver;
use Chevere\Components\Router\RouterCache;
use Chevere\Components\Router\RouterMaker;
use Chevere\Components\Routing\FsRoutesMaker;
use Chevere\Components\Routing\Routing;
use Chevere\Components\Runtime\Runtime;
use Chevere\Components\Runtime\Sets\SetErrorHandler;
use Chevere\Components\Runtime\Sets\SetExceptionHandler;
use Chevere\Components\VarDump\VarDumpMake;
use Chevere\Components\VarExportable\VarExportable;
use Chevere\Components\Writers\StreamWriterFromString;
use Chevere\Components\Writers\Writers;
use Chevere\Examples\HelloWorld\HookHelloWorldController;
use function Chevere\Components\VarDump\getVarDumpConsole;

require 'vendor/autoload.php';

new VarDumpInstance(
    getVarDumpConsole(new StreamWriterFromString('php://stdout', 'w'))
);
$routesDir = new DirFromString(__DIR__ . '/routes/');
$routing = new Routing(
    new FsRoutesMaker($routesDir),
    new RouterMaker
);
$cacheDir = new DirFromString(__DIR__ . '/cache/');
$cache = new Cache($cacheDir);
// Router caching
$routerCache = new RouterCache($cache->getChild('router/'));
$resolver = new Resolver($routerCache);
$routerCache->put($routing->router());
// HooksPlugs caching
$hooksCache = $cache->getChild('plugs/');
$hooksCacheKey = new CacheKey('hooks');
$hooksDir = new DirFromString(dirname(__DIR__) . '/src/');
$hooksMapper = new PlugsMapper($hooksDir, new HookPlugType);
$hooksCache = $hooksCache->withPut(
    $hooksCacheKey,
    new VarExportable($hooksMapper->plugsMap())
);

// xdd($hooksDir->path()->absolute());
