<?php

use function Chevere\Components\Router\route;
use function Chevere\Components\Router\routes;
use Chevere\Examples\HelloWorld\HookHelloWorldController;

return routes(
    route(
        path: '/hello-{name:\S+}',
        GET: new HookHelloWorldController()
    ),
);