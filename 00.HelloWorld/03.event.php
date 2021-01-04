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
use Chevere\Components\Pluggable\Plug\Event\EventListenersQueue;
use Chevere\Components\Pluggable\Plug\Event\EventListenersRunner;
use Chevere\Components\Writer\Writers;
use Chevere\Examples\HelloWorld\EventHelloWorldController;
use Chevere\Examples\HelloWorld\HelloWorldEvent;

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
$runner = new ActionRunner($controller);
$ran = $runner->execute(name: 'World');
$contents = implode(' ', $ran->data());
if ($writers->out()->toString() != HelloWorldEvent::class . '>>>' . $contents) {
    echo "Unexpected event\n";
    echo $writers->out()->toString() . "\n";
    exit(1);
} else {
    echo "Event listened\n";
}
echo "$contents\n";
