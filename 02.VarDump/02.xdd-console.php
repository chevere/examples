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

use Chevere\Components\Instances\VarDumpInstance;
use Chevere\Components\VarDump\VarDumpMake;
use Chevere\Components\Writers\StreamWriterFromString;
use function Chevere\Components\VarDump\getVarDumpConsole;

require 'vendor/autoload.php';

$writer = new StreamWriterFromString('php://stdout', 'w');
new VarDumpInstance(getVarDumpConsole($writer));
xd($writer);
xdd(true, [1, 'string', [new stdClass]]);