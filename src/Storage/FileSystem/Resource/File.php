<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\FileSystem\Resource;

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
    protected string $type = self::TYPE_FILE;

    /**
     * @var ?string
     */
    protected ?string $mimeType = null;

    /**
     * @var int
     */
    protected int $size = 0;


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

        parent::setStream($stream);
        $this->setState(self::STATE_OPENED_STREAM);


        return $this;
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