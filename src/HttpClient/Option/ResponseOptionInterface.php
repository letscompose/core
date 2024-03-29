<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\HttpClient\Option;

use LetsCompose\Core\HttpClient\Response\ResponseInterface;

interface ResponseOptionInterface extends OptionInterface
{
    public function supports(ResponseInterface $response): bool;
    public function process(ResponseInterface $response): ResponseInterface;
}