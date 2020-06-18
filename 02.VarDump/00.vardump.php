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

use Chevere\Components\VarDump\Formatters\ConsoleFormatter;
use Chevere\Components\VarDump\Outputters\ConsoleOutputter;
use Chevere\Components\VarDump\VarDump;
use Chevere\Components\Writer\StreamWriterFromString;

require 'vendor/autoload.php';

$varDump = new VarDump(
    new ConsoleFormatter,
    new ConsoleOutputter
);
$writer = new StreamWriterFromString('php://stdout', 'w');
$varDump->withVars('a var', [], null)->process($writer);
