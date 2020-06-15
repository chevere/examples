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

use Chevere\Components\Controller\ControllerArguments;
use Chevere\Components\Controller\ControllerRunner;
use Chevere\Components\Plugin\Plugs\EventListeners\EventListenersQueue;
use Chevere\Components\Plugin\Plugs\EventListeners\EventListenersRunner;
use Chevere\Components\Writers\Writers;
use Chevere\Examples\HelloWorld\EventHelloWorldController;
use Chevere\Examples\HelloWorld\HelloWorldEvent;
use Chevere\Interfaces\Controller\ControllerInterface;

require 'vendor/autoload.php';

$writers = new Writers;
$controller = new EventHelloWorldController;
$controller = $controller->withEventListenersRunner(
    new EventListenersRunner(
        (new EventListenersQueue)
            ->withAddedEventListener(new HelloWorldEvent),
        $writers
    )
);
/**
 * @var ControllerInterface $controller
 */
$arguments = new ControllerArguments(
    $controller->parameters(),
    ['name' => 'World']
);
$runner = new ControllerRunner($controller);
$ran = $runner->ran($arguments);
echo "\n-----\n";
echo implode(' ', $ran->data());

// Chevere\Examples\HelloWorldEvent>>>Hello, World
// -----
// Hello, World
