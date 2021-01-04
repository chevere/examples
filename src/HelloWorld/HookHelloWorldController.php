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

use Chevere\Components\Pluggable\Plug\Hook\Traits\PluggableHooksTrait;
use Chevere\Components\Pluggable\PluggableAnchors;
use Chevere\Examples\HelloWorld\HelloWorldController;
use Chevere\Interfaces\Parameter\ArgumentsInterface;
use Chevere\Interfaces\Pluggable\PluggableAnchorsInterface;
use Chevere\Interfaces\Pluggable\Plug\Hook\PluggableHooksInterface;
use Chevere\Interfaces\Response\ResponseInterface;

/**
 * @method self withHooksRunner(HooksRunnerInterface $hooksRunner)
 */
final class HookHelloWorldController extends HelloWorldController implements PluggableHooksInterface
{
    use PluggableHooksTrait;

    public static function getHookAnchors(): PluggableAnchorsInterface
    {
        return (new PluggableAnchors)
            ->withAdded('beforeResponse');
    }

    public function run(ArgumentsInterface $arguments): ResponseInterface
    {
        $greet = sprintf('Hello, %s', $arguments->get('name'));
        $this->hook('beforeResponse', $greet);

        return $this->getResponse(greet: $greet);
    }
}
