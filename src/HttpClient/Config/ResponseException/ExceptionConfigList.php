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

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\InvalidLogicException;
use LetsCompose\Core\Exception\NotUniqueException;

class ExceptionConfigList implements ExceptionConfigListInterface
{
    /**
     * @var ?ExceptionConfigInterface[]
     */
    private array $exceptionConfigs = [];

    private ?string $messagePrefix = null;

    private ?string $message = null;

    private ?int $code = null;

    private bool|array $mute = false;

    private string $path;

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): ExceptionConfigList
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @throws ExceptionInterface
     */
    public function addExceptionConfig(ExceptionConfigInterface $exceptionConfig): self
    {

        if ($this->hasExceptionConfig($exceptionConfig->getClass()))
        {
            throw (new NotUniqueException())
                ->setMessage('You try add already configured exception [%s]', $exceptionConfig->getClass())
            ;
        }

        $this->exceptionConfigs[$exceptionConfig->getClass()] = $exceptionConfig;
        return $this;
    }

    public function getDefaultExceptionConfig(): ExceptionConfigInterface|false
    {
        foreach ($this->exceptionConfigs as $config)
        {
            if (!$config->getRaiseWhenResponseCode() && $config->isDefault())
            {
                return $config;
            }
        }
        return false;
    }

    public function getExceptionConfigByRaiseWhenResponseCode(array $codes): ExceptionConfigInterface|false
    {
        foreach ($this->exceptionConfigs as $config)
        {
            if (array_intersect($config->getRaiseWhenResponseCode(), $codes))
            {
                return $config;
            }
        }
        return false;
    }

    public function hasExceptionConfig(string $class): bool
    {
        return false !== ($this->exceptionConfigs[$class] ?? false);
    }

    /**
     * @return string|null
     */
    public function getMessagePrefix(): ?string
    {
        return $this->messagePrefix;
    }

    public function setMessagePrefix(?string $messagePrefix): self
    {
        $this->messagePrefix = $messagePrefix;
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(?int $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getMute(): bool|array
    {
        return $this->mute;
    }

    public function setMute(bool|array $mute): self
    {
        $this->mute = $mute;
        return $this;
    }
}