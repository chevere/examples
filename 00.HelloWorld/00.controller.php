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
use Chevere\Examples\HelloWorld\HelloWorldController;

require 'vendor/autoload.php';

$controller = new HelloWorldController;
$arguments = new ControllerArguments(
    $controller->parameters(),
    ['name' => 'World']
);
$runner = new ControllerRunner($controller);
$execute = $runner->execute($arguments);
$contents = implode(' ', $execute->data());
if ($contents !== 'Hello, World') {
    echo "Unexpected contents\n";
    exit(1);
}
echo "$contents\n";
