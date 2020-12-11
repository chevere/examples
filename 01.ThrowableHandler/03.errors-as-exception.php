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

require 'vendor/autoload.php';

set_error_handler('Chevere\Components\ThrowableHandler\errorsAsExceptions');
try {
    trigger_error('User error', E_USER_ERROR);
} catch (ErrorException $e) {
    echo "Caught an error as exception\n";
}
