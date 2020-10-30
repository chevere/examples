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

use Chevere\Components\Controller\ControllerRunner;
use Chevere\Components\Parameter\Arguments;
use Chevere\Components\Writer\StreamWriter;
use Chevere\Examples\HelloWorld\HelloWorldController;
use function Chevere\Components\Filesystem\fileForPath;
use function Chevere\Components\Writer\streamFor;

require 'vendor/autoload.php';

$filename = __DIR__ . '/' . basename(__FILE__) . '.log';
$file = fileForPath($filename);
$writer = new StreamWriter(streamFor($filename, 'w'));
$controller = new HelloWorldController;
$arguments = new Arguments(
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
