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

use Chevere\Components\Controller\Controller;
use Chevere\Components\Parameter\Parameters;
use Chevere\Components\Parameter\StringParameter;
use Chevere\Components\Regex\Regex;
use Chevere\Interfaces\Parameter\ArgumentsInterface;
use Chevere\Interfaces\Parameter\ParametersInterface;
use Chevere\Interfaces\Response\ResponseInterface;

class HelloWorldController extends Controller
{
    public function getParameters(): ParametersInterface
    {
        return new Parameters(
            name: (new StringParameter)
                    ->withRegex(new Regex('/\S+/'))
        );
    }

    public function getDescription(): string
    {
        return 'It returns Hello, <name>';
    }

    public function getResponseParameters(): ParametersInterface
    {
        return new Parameters(
            greet: new StringParameter
        );
    }

    public function run(ArgumentsInterface $arguments): ResponseInterface
    {
        $greet = sprintf('Hello, %s', $arguments->get('name'));

        return $this->getResponse(greet: $greet);
    }
}
