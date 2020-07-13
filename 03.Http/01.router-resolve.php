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
use Chevere\Interfaces\Controller\ControllerInterface;
use function Chevere\Components\Filesystem\getDirFromString;

// 750 req/s (php -S, no xdebug)

foreach (['vendor/autoload.php', '../vendor/autoload.php'] as $autoload) {
    if (stream_resolve_include_path($autoload)) {
        require $autoload;
        break;
    }
}
$dir = getDirFromString(__DIR__ . '/');
$cacheDir = $dir->getChild('cache/');
$routeCollector = (new Cache($cacheDir->getChild('router/')))
    ->get(new CacheKey('my-route-collector'))
    ->var();
$dispatcher = new RouterDispatcher($routeCollector);
$routed = $dispatcher->dispatch('GET', '/hello-chevere/');
$controllerName = $routed->controllerName()->toString();
$controller = new $controllerName;
// Hooks caching
$plugsMapCache = new PlugsMapCache(
    new Cache($cacheDir->getChild('plugs/hooks/'))
);
$hooksQueue = $plugsMapCache->getPlugsQueueFor(get_class($controller));
/**
 * @var PluggableHooksInterface $controller
 */
$controller = $controller->withHooksRunner(
    new HooksRunner($hooksQueue)
);
/**
 * @var ControllerInterface $controller
 */
$arguments = new ControllerArguments(
    $controller->parameters(),
    $routed->arguments()
);
$runner = new ControllerRunner($controller);
$ran = $runner->execute($arguments);
echo json_encode($ran->data());

// ["Hello, chevere!!"]
