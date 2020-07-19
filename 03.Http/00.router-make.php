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
use Chevere\Components\Plugin\PlugsMapCache;
use Chevere\Components\Plugin\PlugsMapper;
use Chevere\Components\Plugin\Types\HookPlugType;
use Chevere\Components\Routing\RoutingDescriptorsMaker;
use Chevere\Components\VarExportable\VarExportable;
use function Chevere\Components\Filesystem\dirFromString;
use function Chevere\Components\Routing\routerForRoutingDescriptors;

require 'vendor/autoload.php';

$dir = dirFromString(__DIR__ . '/');
$cacheDir = $dir->getChild('cache/');
$maker = new RoutingDescriptorsMaker($dir->getChild('routes/'));
$descriptors = $maker->descriptors();
$router = routerForRoutingDescriptors($descriptors, 'example');
$cacheRouteCollector = (new Cache($cacheDir->getChild('router/')))
    ->withAddedItem(
        new CacheKey('my-route-collector'),
        new VarExportable($router->routeCollector())
    );
echo "Cached my-route-collector\n";
$plugsMapper = new PlugsMapper(
    dirFromString(dirname(__DIR__) . '/src/'),
    new HookPlugType
);
$plugsMapCache = new PlugsMapCache(
    new Cache($cacheDir->getChild('plugs/hooks/'))
);
$plugsMapCache = $plugsMapCache->withPut($plugsMapper->plugsMap());
echo "Cached plugs map\n";
