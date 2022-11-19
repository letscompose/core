<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\FileSystem;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\InvalidArgumentException;
use LetsCompose\Core\Storage\Resource\AbstractResource;
use LetsCompose\Core\Tools\ExceptionHelper;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class File extends AbstractResource implements FileInterface
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
    protected string $type = self::TYPE_FILE;

    /**
     * @var string
     */
    protected string $state = self::STATE_CLOSED_STREAM;

    /**
     * @var mixed
     */
    protected mixed $stream;

    /**
     * @var ?string
     */
    protected ?string $mimeType = null;

    /**
     * @var int
     */
    protected int $size = 0;

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
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
     * @return $this
     */
    public function setPath(string $path): self
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
     * @return $this
     * @throws ExceptionInterface
     */
    public function setType(string $type): self
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
     * @return $this
     * @throws ExceptionInterface
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

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param mixed $stream
     * @return $this
     * @throws ExceptionInterface
     */
    public function setStream(mixed $stream): self
    {
        if (!is_resource($stream) && 'stream' !== get_resource_type($stream))
        {
            ExceptionHelper
                ::create(new InvalidArgumentException('You try to assign closed or not valid stream resource to File object'))
                ->throw();
        }

        $this->stream = $stream;
        $this->setState(self::STATE_OPENED_STREAM);


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
        return self::STATE_OPENED_STREAM === $this->getState();
    }

    /**
     * @inheritDoc
     */
    public function getExtension(): ?string
    {
        return pathinfo($this->getPath(), PATHINFO_EXTENSION);
    }

    /**
     * @param string|null $mimeType
     * @return File
     */
    public function setMimeType(?string $mimeType): File
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * @param int $size
     * @return File
     */
    public function setSize(int $size): File
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

}