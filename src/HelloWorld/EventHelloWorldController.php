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

use Chevere\Components\Pluggable\Plug\Event\Traits\PluggableEventsTrait;
use Chevere\Components\Pluggable\PluggableAnchors;
use Chevere\Examples\HelloWorld\HelloWorldController;
use Chevere\Interfaces\Parameter\ArgumentsInterface;
use Chevere\Interfaces\Pluggable\PluggableAnchorsInterface;
use Chevere\Interfaces\Pluggable\Plug\Event\PluggableEventsInterface;
use Chevere\Interfaces\Response\ResponseInterface;

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

    public function run(ArgumentsInterface $arguments): ResponseInterface
    {
        $greet = sprintf('Hello, %s', $arguments->get('name'));
        $this->event('greetSet', [$greet]);

        return $this->getResponse(greet: $greet);
    }
}
