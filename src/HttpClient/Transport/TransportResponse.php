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

use \Throwable;

class TransportResponse implements TransportResponseInterface
{
    public function __construct
    (
        private readonly int $statusCode,
        private readonly ?array $headers,
        private readonly mixed $content,
        private readonly ?Throwable $exception = null
    )
    {
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(): ?array
    {
        return $this->headers;
    }

    public function getContent(): mixed
    {
        return $this->content;
    }

    public function getException(): ?Throwable
    {
        return $this->exception;
    }

    public function hasException(): bool
    {
        return null !== $this->exception;
    }


}