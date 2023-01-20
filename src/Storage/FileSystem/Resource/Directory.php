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
use LetsCompose\Core\Tools\ExceptionHelper;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class Directory extends AbstractFileSystemResource implements DirectoryInterface
{
    private ?DirectoryInfoInterface $info = null;

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
                ::create(new InvalidArgumentException('You try to assign closed or not valid stream resource to Directory object'))
                ->throw();
        }

        $this->stream = $stream;
        $this->setState(self::STATE_OPENED_STREAM);

        return $this;
    }

    /**
     * @return DirectoryInfo|null
     */
    public function getInfo(): ?DirectoryInfoInterface
    {
        return $this->info;
    }

    /**
     * @param DirectoryInfo|null $info
     * @return Directory
     */
    public function setInfo(?DirectoryInfoInterface $info): self
    {
        $this->info = $info;
        return $this;
    }



}