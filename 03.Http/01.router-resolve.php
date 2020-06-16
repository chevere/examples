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
use Chevere\Components\Filesystem\DirFromString;
use Chevere\Components\Plugin\Plugs\Hooks\HooksRunner;
use Chevere\Components\Plugin\PlugsMapCache;
use Chevere\Components\Router\Resolver;
use Chevere\Components\Router\RouterCache;
use Chevere\Interfaces\Controller\ControllerInterface;
use Laminas\Diactoros\Uri;

foreach (['../vendor/autoload.php', 'vendor/autoload.php'] as $autoload) {
    if (stream_resolve_include_path($autoload)) {
        require $autoload; // 241 req/s (Internal)
        break;
    }
}
$cacheDir = new DirFromString(__DIR__ . '/cache/');
$cache = new Cache($cacheDir);
// Router caching
$routerCache = new RouterCache($cache->getChild('router/'));
$resolver = new Resolver($routerCache);
$uri = new Uri('/hello-chevere/');
$routed = $resolver->resolve($uri);
$routeName = $routed->name()->toString();
$route = $routerCache->routesCache()->get($routeName);
$endpoint = $route->endpoints()->get('GET');
$controller = $endpoint->controller();
// Hooks caching
$hooksCache = new PlugsMapCache($cache->getChild('plugs/hooks/'));
$hooksQueue = $hooksCache->getPlugsQueueFor(get_class($controller));
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
$ran = $runner->ran($arguments);
echo json_encode($ran->data());

// ["Hello, chevere!!"]
