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
use Chevere\Examples\HelloWorld\HelloWorldController;

require 'vendor/autoload.php';

$controller = new HelloWorldController;
$runner = new ActionRunner($controller);
$execute = $runner->execute(name: 'World');
$contents = implode(' ', $execute->data());
if ($contents !== 'Hello, World') {
    echo "Unexpected contents\n";
}
echo "$contents\n";
exit(1);
