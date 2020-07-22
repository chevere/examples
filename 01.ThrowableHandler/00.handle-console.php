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

use Chevere\Components\Message\Message;
use Chevere\Components\ThrowableHandler\Documents\ThrowableHandlerConsoleDocument;
use Chevere\Components\ThrowableHandler\ThrowableHandler;
use Chevere\Components\ThrowableHandler\ThrowableRead;
use Chevere\Exceptions\Core\InvalidArgumentException;

require 'vendor/autoload.php';

try {
    try {
        throw new Exception('Chained invalid argument.');
    } catch (Exception $e) {
        throw new InvalidArgumentException(
            new Message('Whoops...'),
            666,
            $e
        );
    }
} catch (Exception $e) {
    $handler = new ThrowableHandler(new ThrowableRead($e));
    $document = new ThrowableHandlerConsoleDocument($handler);
    echo $document->toString() . "\n";
}
