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
use Chevere\Components\Filesystem\FileFromString;
use Chevere\Components\Writers\StreamWriterFromString;
use Chevere\Examples\HelloWorld\HelloWorldController;

require 'vendor/autoload.php';

$file = new FileFromString(__DIR__ . '/' . basename(__FILE__) . '.log');
$writer = new StreamWriterFromString($file->path()->absolute(), 'w');
$controller = new HelloWorldController;
$arguments = new ControllerArguments(
    $controller->parameters(),
    ['name' => 'World']
);
$runner = new ControllerRunner($controller);
$ran = $runner->ran($arguments);
$writer->write(implode(' ', $ran->data()));
echo 'Wrote: ' . $file->path()->absolute() . "\n";

// Wrote: <path>
