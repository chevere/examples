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
use Chevere\Components\Pluggable\PlugsMapCache;
use Chevere\Components\Pluggable\PlugsMapper;
use Chevere\Components\Pluggable\Types\HookPlugType;
use Chevere\Components\Router\Router;
use Chevere\Components\Router\Routing\RoutingDescriptorsMaker;
use Chevere\Components\Var\VarStorable;

use function Chevere\Components\Filesystem\dirForPath;
use function Chevere\Components\Router\importRoutes;
use function Chevere\Components\Router\router;
use function Chevere\Components\Router\Routing\routerForRoutingDescriptors;

require 'vendor/autoload.php';

set_exception_handler('Chevere\Components\ThrowableHandler\consoleHandler');

$dir = dirForPath(__DIR__ . '/');
$cacheDir = $dir->getChild('cache/');
$router = router(
    group: 'example',
    routes: importRoutes(__DIR__ . '/routes/web.php')
);
$cacheRouteCollector = (new Cache($cacheDir->getChild('router/')))
    ->withPut(
        new CacheKey('my-route-collector'),
        new VarStorable($router->routeCollector())
    );
echo "Cached my-route-collector\n";
$plugsMapper = new PlugsMapper(new HookPlugType);
$plugsMapper = $plugsMapper
    ->withPlugsMapFor(dirForPath(dirname(__DIR__) . '/src/'));
$plugsMapCache = new PlugsMapCache(
    new Cache($cacheDir->getChild('plugs/hooks/'))
);
$plugsMapCache = $plugsMapCache->withPut($plugsMapper->plugsMap());
echo "Cached plugs map\n";
