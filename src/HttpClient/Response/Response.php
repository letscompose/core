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

use Exception;
use LetsCompose\Core\HttpClient\Config\Response\ResponseCodeHelper;
use LetsCompose\Core\HttpClient\Config\Response\ResponseConfigInterface;
use LetsCompose\Core\HttpClient\Config\ResponseException\ExceptionConfig;
use LetsCompose\Core\HttpClient\Config\ResponseException\ExceptionConfigInterface;

class Response implements ResponseInterface
{
    private ?ResponseConfigInterface $config;

    private ?ExceptionConfig $exceptionConfig = null;

    private ?int $statusCode = null;

    private array $headers = [];

    private mixed $content = null;

    public function getConfig(): ?ResponseConfigInterface
    {
        return $this->config;
    }

    public function setConfig(?ResponseConfigInterface $config): self
    {
        $this->config = $config;
        return $this;
    }

    public function getExceptionConfig(): ?ExceptionConfigInterface
    {
        return $this->exceptionConfig;
    }

    public function hasException(): bool
    {
        return null !== $this->exceptionConfig;
    }

    public function setExceptionConfig(?ExceptionConfigInterface $exceptionConfig): self
    {
        $this->exceptionConfig = $exceptionConfig;
        return $this;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function setStatusCode(?int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function getContent(bool $muteException = false): mixed
    {
        $this->throwException($muteException);
        return $this->content;
    }

    public function setContent(mixed $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function isValid():bool
    {
        return ResponseCodeHelper::isSuccessful($this->statusCode) && false === $this->hasException();
    }


    /**
     * @throws Exception
     */
    public function throwException(bool $mute): void
    {
        if ($mute || $this->isValid())
        {
            return;
        }

        $exceptionConfig = $this->exceptionConfig;

        throw new ($exceptionConfig->getClass())
        (
            $exceptionConfig->getMessage(),
            $exceptionConfig->getCode(),
            $exceptionConfig->getPrevious(),

        );
    }

}