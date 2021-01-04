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
use Chevere\Components\Pluggable\Plug\Hook\HooksQueue;
use Chevere\Components\Pluggable\Plug\Hook\HooksRunner;
use Chevere\Examples\HelloWorld\HelloWorldHook;
use Chevere\Examples\HelloWorld\HookHelloWorldController;

require 'vendor/autoload.php';

$controller = new HookHelloWorldController;
$controller = $controller->withHooksRunner(
    new HooksRunner(
        (new HooksQueue)
            ->withAdded(new HelloWorldHook)
    )
);
$runner = new ActionRunner($controller);
$ran = $runner->execute(name: 'World');
$contents = implode(' ', $ran->data());
if ($contents !== 'Hello, World!!') {
    echo "Unexpected contents\n";
}
echo "$contents\n";
exit(1);
