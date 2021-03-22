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
use function Chevere\Components\Filesystem\dirForPath;

// no xdebug!
// 419.86 req/s (php -S)
// 884.46 req/s (Apache 2.4)

foreach (['vendor/autoload.php', '../vendor/autoload.php'] as $autoload) {
    if (stream_resolve_include_path($autoload)) {
        require $autoload;
        break;
    }
}
$dir = dirForPath(__DIR__ . '/');
$cacheDir = $dir->getChild('cache/');
$routeCollector = (new Cache($cacheDir->getChild('router/')))
    ->get(new CacheKey('my-route-collector'))
    ->var();
$dispatcher = new RouterDispatcher($routeCollector);
$routed = $dispatcher->dispatch('GET', '/🐘-hello-chevere');
$controllerName = $routed->controllerName()->toString();
$controller = new $controllerName;
$plugsMapCache = new PlugsMapCache(
    new Cache($cacheDir->getChild('plugs/hooks/'))
);
$hooksQueue = $plugsMapCache->getPlugsQueueTypedFor(get_class($controller));
$controller = $controller->withHooksRunner(
    new HooksRunner($hooksQueue)
);
$runner = new ActionRunner($controller);
$ran = $runner->execute(name: 'chevere');
echo json_encode($ran->data());