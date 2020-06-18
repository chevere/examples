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

use Chevere\Components\ThrowableHandler\Handle;
use Chevere\Components\Instances\RuntimeInstance;
use Chevere\Components\Instances\WritersInstance;
use Chevere\Components\Runtime\Runtime;
use Chevere\Components\Runtime\Sets\SetErrorHandler;
use Chevere\Components\Runtime\Sets\SetExceptionHandler;
use Chevere\Components\Writer\Writers;

require 'vendor/autoload.php';

new WritersInstance(new Writers);
new RuntimeInstance(
    (new Runtime)
        ->withSet(new SetErrorHandler(Handle::class . '::errorsAsExceptions'))
        ->withSet(new SetExceptionHandler(Handle::class . '::console'))
);
1 / 0;
