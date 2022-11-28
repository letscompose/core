<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\Resource;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\InvalidArgumentException;
use LetsCompose\Core\Tools\ExceptionHelper;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
abstract class AbstractResource implements ResourceInterface
{
    protected string $name;

    protected string $path;

    protected string $state = self::STATE_CLOSED_STREAM;

    /**
     * @var Resource
     */
    protected mixed $stream;

    protected string $storageClass;

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @throws InvalidArgumentException|ExceptionInterface
     */
    public function setState(string $state): self
    {
        if (false === \in_array($state, self::STATE_MAP))
        {
            ExceptionHelper
                ::create(new InvalidArgumentException())
                ->message('Unknown resource state, state can be only one of theses [%s]', implode(',', self::STATE_MAP))
                ->throw();
        }
        $this->state = $state;
        return $this;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setStream(mixed $stream): self
    {
        $this->stream = $stream;
        return $this;
    }

    public function getStream(): mixed
    {
        return $this->stream;
    }

    public function isOpen(): bool
    {
        return self::STATE_OPENED_STREAM === $this->getState();
    }

    public function getStorageClass(): string
    {
        return $this->storageClass;
    }

    /**
     * @throws InvalidArgumentException|ExceptionInterface
     */
    public function setStorageClass(string $class): self
    {
        if (false === class_exists($class))
        {
            ExceptionHelper::create(new InvalidArgumentException())
                ->message('You try bind File to unknown storage class [%s]', $class)
                ->throw()
                ;
        }
        $this->storageClass = $class;
        return $this;
    }
}