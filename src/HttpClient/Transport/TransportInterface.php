<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace LetsCompose\Core\HttpClient\Transport;

use LetsCompose\Core\HttpClient\Request\RequestInterface;

interface TransportInterface
{
    public function send(RequestInterface $request): TransportResponseInterface;
}