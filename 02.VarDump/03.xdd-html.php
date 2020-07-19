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
use Chevere\Components\Writer\StreamWriter;
use function Chevere\Components\VarDump\varDumpHtml;
use function Chevere\Components\Writer\streamFor;

require 'vendor/autoload.php';

$writer = new StreamWriter(streamFor('php://stdout', 'w'));
new VarDumpInstance(varDumpHtml());
xdd(true, [1, 'string', [new stdClass]]);
