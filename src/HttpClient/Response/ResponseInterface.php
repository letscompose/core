<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\HttpClient\Response;

use LetsCompose\Core\HttpClient\Config\Response\ResponseConfigInterface;
use LetsCompose\Core\HttpClient\Config\ResponseException\ExceptionConfigInterface;

interface ResponseInterface
{
    public function getConfig(): ?ResponseConfigInterface;
    public function setConfig(?ResponseConfigInterface $config): self;
    public function getStatusCode(): ?int;
    public function setStatusCode(?int $statusCode): self;
    public function getExceptionConfig(): ?ExceptionConfigInterface;
    public function setExceptionConfig(?ExceptionConfigInterface $exceptionConfig): self;
    public function isValid(): bool;
    public function hasException(): bool;
    public function throwException(bool $mute): void;
    public function getHeaders(): array;
    public function setHeaders(array $headers): self;
    public function getContent(bool $muteException = false): mixed;
    public function setContent(mixed $content): self;
    public function addOption(string $optionClass): self;
    public function hasOption(string $optionClass): bool;
}