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

use Throwable;

interface TransportResponseInterface
{
    public function getStatusCode(): int;

    public function getContent(): mixed;

    public function getHeaders(): ?array;

    public function getException(): ?Throwable;

    public function hasException(): bool;
}