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
use Chevere\Components\Response\ResponseSuccess;
use Chevere\Examples\HelloWorld\HelloWorldController;
use Chevere\Interfaces\Controller\ControllerArgumentsInterface;
use Chevere\Interfaces\Controller\ControllerResponseInterface;
use Chevere\Interfaces\Parameter\ArgumentsInterface;
use Chevere\Interfaces\Plugin\PluggableAnchorsInterface;
use Chevere\Interfaces\Plugin\Plugs\EventListener\PluggableEventsInterface;
use Chevere\Interfaces\Response\ResponseInterface;
use Chevere\Interfaces\Response\ResponseSuccessInterface;

/**
 * @method self withEventListenersRunner(EventListenersRunnerInterface $eventsRunner)
 */
final class EventHelloWorldController extends HelloWorldController implements PluggableEventsInterface
{
    use PluggableEventsTrait;

    public static function getEventAnchors(): PluggableAnchorsInterface
    {
        return (new PluggableAnchors)
            ->withAdded('greetSet');
    }

    public function run(ArgumentsInterface $arguments): ResponseSuccessInterface
    {
        $greet = sprintf('Hello, %s', $arguments->get('name'));
        $this->event('greetSet', [$greet]);

        return $this->getResponseSuccess(['greet' => $greet]);
    }
}
