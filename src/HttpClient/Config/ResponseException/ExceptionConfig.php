<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace LetsCompose\Core\HttpClient\Config\ResponseException;

class ExceptionConfig implements ExceptionConfigInterface
{
    private string $class;

    private int $code;

    private string $message;

    private ?string $messagePrefix = null;

    private array $whenResponseCode = [];

    private bool $default = false;

    public function getClass(): string
    {
        return $this->class;
    }

    public function setClass(string $class): self
    {
        $this->class = $class;
        return $this;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getMessagePrefix(): ?string
    {
        return $this->messagePrefix;
    }

    public function setMessagePrefix(?string $messagePrefix): ExceptionConfig
    {
        $this->messagePrefix = $messagePrefix;
        return $this;
    }

    public function getWhenResponseCode(): array
    {
        return $this->whenResponseCode;
    }

    public function setWhenResponseCode(array $whenResponseCode): self
    {
        $this->whenResponseCode = $whenResponseCode;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->default;
    }

    /**
     * @param bool $default
     * @return ExceptionConfig
     */
    public function setDefault(bool $default): ExceptionConfig
    {
        $this->default = $default;
        return $this;
    }
}