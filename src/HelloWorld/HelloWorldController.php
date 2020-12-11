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

namespace Chevere\Examples\HelloWorld;

use Chevere\Components\Action\Controller;
use Chevere\Components\Parameter\Parameters;
use Chevere\Components\Parameter\StringParameter;
use Chevere\Components\Regex\Regex;
use Chevere\Components\Response\ResponseSuccess;
use Chevere\Interfaces\Parameter\ArgumentsInterface;
use Chevere\Interfaces\Parameter\ParametersInterface;
use Chevere\Interfaces\Response\ResponseInterface;
use Chevere\Interfaces\Response\ResponseSuccessInterface;

class HelloWorldController extends Controller
{
    public function getParameters(): ParametersInterface
    {
        return (new Parameters)
            ->withAddedRequired(
                name: (new StringParameter)
                    ->withRegex(new Regex('/\w+/'))
            );
    }

    public function getDescription(): string
    {
        return 'It returns Hello, <name>';
    }

    public function getResponseDataParameters(): ParametersInterface
    {
        return (new Parameters)
            ->withAddedRequired(
                greet: new StringParameter
            );
    }

    public function run(ArgumentsInterface $arguments): ResponseSuccessInterface
    {
        $greet = sprintf('Hello, %s', $arguments->get('name'));

        return $this->getResponseSuccess(['greet' => $greet]);
    }
}
