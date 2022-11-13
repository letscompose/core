<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\Object;

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
     * @return AbstractResource
     */
    public function setType(string $type): ResourceInterface
    {
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
     * @return AbstractResource
     */
    public function setState(string $state): ResourceInterface
    {
        if (false === \in_array($state, self::STATE_MAP))
        {
            throw new \InvalidArgumentException();
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

}