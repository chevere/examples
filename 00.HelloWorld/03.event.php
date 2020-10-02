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
use Chevere\Components\Parameter\Arguments;
use Chevere\Components\Plugin\Plugs\EventListeners\EventListenersQueue;
use Chevere\Components\Plugin\Plugs\EventListeners\EventListenersRunner;
use Chevere\Components\Writer\Writers;
use Chevere\Examples\HelloWorld\EventHelloWorldController;
use Chevere\Examples\HelloWorld\HelloWorldEvent;
use Chevere\Interfaces\Controller\ControllerInterface;

require 'vendor/autoload.php';

$writers = new Writers;
$controller = new EventHelloWorldController;
$controller = $controller->withEventListenersRunner(
    new EventListenersRunner(
        (new EventListenersQueue)
            ->withAdded(new HelloWorldEvent),
        $writers
    )
);
/**
 * @var ControllerInterface $controller
 */
$arguments = new Arguments(
    $controller->parameters(),
    ['name' => 'World']
);
$runner = new ControllerRunner($controller);
$ran = $runner->execute($arguments);
$contents = implode(' ', $ran->data());
if ($writers->out()->toString() != HelloWorldEvent::class . '>>>' . $contents) {
    echo "Unexpected event\n";
    exit(1);
} else {
    echo "Event listened\n";
}
echo "$contents\n";
