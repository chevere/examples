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

namespace Chevere\Examples\HelloWorld;

use Chevere\Components\Controller\ControllerResponse;
use Chevere\Components\Plugin\PluggableAnchors;
use Chevere\Components\Plugin\Plugs\EventListeners\Traits\PluggableEventsTrait;
use Chevere\Examples\HelloWorld\HelloWorldController;
use Chevere\Interfaces\Controller\ControllerArgumentsInterface;
use Chevere\Interfaces\Controller\ControllerResponseInterface;
use Chevere\Interfaces\Plugin\PluggableAnchorsInterface;
use Chevere\Interfaces\Plugin\Plugs\EventListener\PluggableEventsInterface;

final class EventHelloWorldController extends HelloWorldController implements PluggableEventsInterface
{
    use PluggableEventsTrait;

    public static function getEventAnchors(): PluggableAnchorsInterface
    {
        return (new PluggableAnchors)
            ->withAddedAnchor('greetSet');
    }

    public function run(ControllerArgumentsInterface $controllerArguments): ControllerResponseInterface
    {
        $greet = sprintf('Hello, %s', $controllerArguments->get('name'));
        $this->event('greetSet', [$greet]);

        return (new ControllerResponse(true))
            ->withData([$greet]);
    }
}
