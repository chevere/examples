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
use function Chevere\Components\VarDump\getVarDumpConsole;

require 'vendor/autoload.php';

new VarDumpInstance(getVarDumpConsole());
xdd(true, [1, 'string', [new stdClass]]);
