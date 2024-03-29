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
use LetsCompose\Core\Storage\FileSystem\Enum\FileOpenModeEnum;
use LetsCompose\Core\Storage\Resource\ResourceInfoInterface;
use LetsCompose\Core\Tools\ExceptionHelper;
use UnitEnum;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class File extends AbstractFileSystemResource implements FileInterface
{

    protected ?string $mimeType = null;

    protected ?FileInfoInterface $info = null;

    /**
     * @throws InvalidArgumentException
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

    public function isStreamMode(UnitEnum|FileOpenModeEnum $mode): bool
    {
        if ($this->isOpen())
        {
            $metadata = stream_get_meta_data($this->getStream());
            $mode->mode($mode);
        }
        return false;
    }


    public function getExtension(): ?string
    {
        return pathinfo($this->getPath(), PATHINFO_EXTENSION);
    }

    public function getDirectoryPath(): string
    {
        return pathinfo($this->getPath(), PATHINFO_DIRNAME);
    }

    public function getName(): string
    {
        return pathinfo($this->getPath(), PATHINFO_BASENAME);
    }

    public function setMimeType(?string $mimeType): self
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * @return FileInfoInterface|null
     */
    public function getInfo(): ?FileInfoInterface
    {
        return $this->info;
    }

    public function setInfo(?FileInfoInterface $info): self
    {
        $this->info = $info;
        return $this;
    }
}