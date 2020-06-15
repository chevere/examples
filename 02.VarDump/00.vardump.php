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

use Chevere\Components\Writers\StreamWriterFromString;
use function Chevere\Components\VarDump\getVarDumpConsole;
use function Chevere\Components\VarDump\getVarDumpPlain;

require 'vendor/autoload.php';

$writer = new StreamWriterFromString('php://stdout', 'w');
getVarDumpPlain($writer)->withVars('a var')->process();
