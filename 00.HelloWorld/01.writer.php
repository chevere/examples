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
use Chevere\Components\Writer\StreamWriterFromString;
use Chevere\Examples\HelloWorld\HelloWorldController;
use function Chevere\Components\Filesystem\getFileFromString;

require 'vendor/autoload.php';

$file = getFileFromString(__DIR__ . '/' . basename(__FILE__) . '.log');
$writer = new StreamWriterFromString($file->path()->absolute(), 'w');
$controller = new HelloWorldController;
$arguments = new ControllerArguments(
    $controller->parameters(),
    ['name' => 'World']
);
$runner = new ControllerRunner($controller);
$ran = $runner->execute($arguments);
$contents = implode(' ', $ran->data());
$writer->write($contents);
if ($file->contents() !== $contents) {
    echo "Failed to write contents\n";
    exit(1);
}
if ($contents !== 'Hello, World') {
    echo "Unexpected contents\n";
    exit(1);
}
echo 'Wrote: ' . $file->path()->absolute() . "\n";
