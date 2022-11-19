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

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string
     */
    protected string $path;

    /**
     * @var string
     */
    protected string $type;

    /**
     * @var string
     */
    protected string $state;

    /**
     * @var mixed
     */
    protected mixed $stream;

    /**
     * @var string
     */
    protected string $storageClass;

    /**
     * @param string $name
     * @return AbstractResource
     */
    public function setName(string $name): ResourceInterface
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $path
     * @return AbstractResource
     */
    public function setPath(string $path): ResourceInterface
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $type
     * @return ResourceInterface
     * @throws ExceptionInterface
     */
    public function setType(string $type): ResourceInterface
    {
        if (false === \in_array($type, self::TYPE_MAP))
        {
            ExceptionHelper
                ::create(new InvalidArgumentException())
                ->message('Unknown resource type, type can be only one of theses [%s]', implode(',', self::TYPE_MAP))
                ->throw();
        }
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $state
     * @return ResourceInterface
     * @throws ExceptionInterface
     */
    public function setState(string $state): ResourceInterface
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

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param mixed $stream
     * @return AbstractResource
     */
    public function setStream(mixed $stream): ResourceInterface
    {
        $this->stream = $stream;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStream(): mixed
    {
        return $this->stream;
    }

    /**
     * @inheritDoc
     */
    public function isOpen(): bool
    {
        return self::STATE_CLOSED_STREAM === $this->getState();
    }

    /**
     * @return string
     */
    public function getStorageClass(): string
    {
        return $this->storageClass;
    }

    /**
     * @param string $class
     * @return AbstractResource
     * @throws ExceptionInterface
     */
    public function setStorageClass(string $class): AbstractResource
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