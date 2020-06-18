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

use Chevere\Components\Filesystem\FileFromString;
use Chevere\Components\ThrowableHandler\Documents\HtmlDocument;
use Chevere\Components\ThrowableHandler\ThrowableHandler;
use Chevere\Components\ThrowableHandler\ThrowableRead;
use Chevere\Components\Writer\StreamWriterFromString;

require 'vendor/autoload.php';

$baseFilePath = __DIR__ . '/' . basename(__FILE__);
$htmlLoud = new FileFromString($baseFilePath . '-loud.html');
$htmlSilent = new FileFromString($baseFilePath . '-silent.html');
$loudWriter = new StreamWriterFromString($htmlLoud->path()->absolute(), 'w');
$silentWriter = new StreamWriterFromString($htmlSilent->path()->absolute(), 'w');
try {
    throw new Exception('Whoops...');
} catch (Exception $e) {
    $handler = new ThrowableHandler(new ThrowableRead($e));
    $docLoud = new HtmlDocument($handler);
    $docSilent = new HtmlDocument($handler->withIsDebug(false));
    $loudWriter->write($docLoud->toString());
    $silentWriter->write($docSilent->toString());
}
