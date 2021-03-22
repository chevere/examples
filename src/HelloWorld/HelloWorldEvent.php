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

use Chevere\Interfaces\Pluggable\Plug\Event\EventInterface;
use Chevere\Interfaces\Writer\WritersInterface;

final class HelloWorldEvent implements EventInterface
{
    public function __invoke(array $data, WritersInterface $writers): void
    {
        $writers->output()->write(__CLASS__ . '>>>' . implode(' ', $data));
    }

    public function anchor(): string
    {
        return 'greetSet';
    }

    public function at(): string
    {
        return EventHelloWorldController::class;
    }

    public function priority(): int
    {
        return 0;
    }
}
