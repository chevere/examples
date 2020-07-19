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

use Chevere\Components\ThrowableHandler\Documents\ThrowableHandlerHtmlDocument;
use Chevere\Components\ThrowableHandler\ThrowableHandler;
use Chevere\Components\ThrowableHandler\ThrowableRead;
use function Chevere\Components\Filesystem\fileFromString;
use function Chevere\Components\Writer\writerForFile;

require 'vendor/autoload.php';

$baseFilePath = __DIR__ . '/' . basename(__FILE__);
$htmlLoud = fileFromString($baseFilePath . '-loud.html');
$htmlSilent = fileFromString($baseFilePath . '-silent.html');
$loudWriter = writerForFile($htmlLoud, 'w');
$silentWriter = writerForFile($htmlSilent, 'w');
try {
    throw new Exception('Whoops...');
} catch (Exception $e) {
    $handler = new ThrowableHandler(new ThrowableRead($e));
    $docLoud = new ThrowableHandlerHtmlDocument($handler);
    $docSilent = new ThrowableHandlerHtmlDocument($handler->withIsDebug(false));
    $loudWriter->write($docLoud->toString());
    $silentWriter->write($docSilent->toString());
}
