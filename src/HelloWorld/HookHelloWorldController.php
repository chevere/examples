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

use Chevere\Components\Plugin\PluggableAnchors;
use Chevere\Components\Plugin\Plugs\Hooks\Traits\PluggableHooksTrait;
use Chevere\Components\Response\ResponseSuccess;
use Chevere\Examples\HelloWorld\HelloWorldController;
use Chevere\Interfaces\Parameter\ArgumentsInterface;
use Chevere\Interfaces\Plugin\PluggableAnchorsInterface;
use Chevere\Interfaces\Plugin\Plugs\Hooks\PluggableHooksInterface;
use Chevere\Interfaces\Response\ResponseInterface;
use Chevere\Interfaces\Response\ResponseSuccessInterface;

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

    public function run(ArgumentsInterface $arguments): ResponseSuccessInterface
    {
        $greet = sprintf('Hello, %s', $arguments->get('name'));
        $this->hook('beforeResponse', $greet);

        return $this->getResponseSuccess(['greet' => $greet]);
    }
}
