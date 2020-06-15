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

use Chevere\Components\ExceptionHandler\Documents\HtmlDocument;
use Chevere\Components\ExceptionHandler\Documents\PlainDocument;
use Chevere\Components\ExceptionHandler\ExceptionHandler;
use Chevere\Components\ExceptionHandler\ExceptionRead;
use Chevere\Components\Filesystem\FileFromString;
use Chevere\Components\Writers\StreamWriterFromString;

require 'vendor/autoload.php';

$baseFilePath = __DIR__ . '/' . basename(__FILE__);
$htmlLoud = new FileFromString($baseFilePath . '-loud.html');
$htmlSilent = new FileFromString($baseFilePath . '-silent.html');
$loudWriter = new StreamWriterFromString($htmlLoud->path()->absolute(), 'w');
$silentWriter = new StreamWriterFromString($htmlSilent->path()->absolute(), 'w');
try {
    throw new Exception('Whoops...');
} catch (Exception $e) {
    $handler = new ExceptionHandler(new ExceptionRead($e));
    $docLoud = new HtmlDocument($handler->withIsDebug(true));
    $docSilent = new HtmlDocument($handler);
    $loudWriter->write($docLoud->toString());
    $silentWriter->write($docSilent->toString());
}
