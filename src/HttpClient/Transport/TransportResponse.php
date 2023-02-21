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

class TransportResponse implements TransportResponseInterface
{
    public function __construct
    (
        private readonly ?array $headers,
        private readonly mixed $data,
    )
    {
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getHeaders(): ?array
    {
        return $this->headers;
    }
}