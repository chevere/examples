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
use Chevere\Components\Filesystem\DirFromString;
use Chevere\Components\Plugin\PlugsMapCache;
use Chevere\Components\Plugin\PlugsMapper;
use Chevere\Components\Plugin\Types\HookPlugType;
use Chevere\Components\Router\Resolver;
use Chevere\Components\Router\Router;
use Chevere\Components\Router\RouterCache;
use Chevere\Components\Routing\FsRoutesMaker;
use Chevere\Components\Routing\Routing;
use function Chevere\Components\Filesystem\getDirFromString;

require 'vendor/autoload.php';

$routing = new Routing(
    new FsRoutesMaker(
        getDirFromString(__DIR__ . '/routes/')
    ),
    new Router
);
$cache = new Cache(getDirFromString(__DIR__ . '/cache/'));
// Router caching
$routerCache = new RouterCache($cache->getChild('router/'));
$resolver = new Resolver($routerCache);
$routerCache->withPut($routing->router());
// Hooks caching
$plugsMapCache = new PlugsMapCache($cache->getChild('plugs/hooks/'));
$plugsMapper = new PlugsMapper(
    getDirFromString(dirname(__DIR__) . '/src/'),
    new HookPlugType
);
$plugsMapCache->withPut($plugsMapper->plugsMap());
