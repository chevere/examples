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
use Chevere\Components\Filesystem\DirFromString;
use Chevere\Components\Instances\VarDumpInstance;
use Chevere\Components\Plugin\PlugsMapCache;
use Chevere\Components\Plugin\PlugsMapper;
use Chevere\Components\Plugin\Types\HookPlugType;
use Chevere\Components\Router\Resolver;
use Chevere\Components\Router\RouterCache;
use Chevere\Components\Router\RouterMaker;
use Chevere\Components\Routing\FsRoutesMaker;
use Chevere\Components\Routing\Routing;
use Chevere\Components\Writers\StreamWriterFromString;
use function Chevere\Components\VarDump\getVarDumpConsole;

require 'vendor/autoload.php';

new VarDumpInstance(
    getVarDumpConsole(new StreamWriterFromString('php://stdout', 'w'))
);
$routing = new Routing(
    new FsRoutesMaker(
        new DirFromString(__DIR__ . '/routes/')
    ),
    new RouterMaker
);
$cache = new Cache(new DirFromString(__DIR__ . '/cache/'));
// Router caching
$routerCache = new RouterCache($cache->getChild('router/'));
$resolver = new Resolver($routerCache);
$routerCache->withPut($routing->router());
// Hooks caching
$hooksCache = new PlugsMapCache($cache->getChild('plugs/hooks/'));
$plugsMapper = new PlugsMapper(
    new DirFromString(dirname(__DIR__) . '/src/'),
    new HookPlugType
);
$hooksCache->withPut($plugsMapper->plugsMap());
