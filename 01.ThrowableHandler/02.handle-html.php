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
use Chevere\Components\Writer\StreamWriterFromString;
use function Chevere\Components\Filesystem\getFileFromString;

require 'vendor/autoload.php';

$baseFilePath = __DIR__ . '/' . basename(__FILE__);
$htmlLoud = getFileFromString($baseFilePath . '-loud.html');
$htmlSilent = getFileFromString($baseFilePath . '-silent.html');
$loudWriter = new StreamWriterFromString($htmlLoud->path()->absolute(), 'w');
$silentWriter = new StreamWriterFromString($htmlSilent->path()->absolute(), 'w');
try {
    throw new Exception('Whoops...');
} catch (Exception $e) {
    $handler = new ThrowableHandler(new ThrowableRead($e));
    $docLoud = new ThrowableHandlerHtmlDocument($handler);
    $docSilent = new ThrowableHandlerHtmlDocument($handler->withIsDebug(false));
    $loudWriter->write($docLoud->toString());
    $silentWriter->write($docSilent->toString());
}
