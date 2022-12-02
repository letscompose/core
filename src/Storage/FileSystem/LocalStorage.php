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
use LetsCompose\Core\Storage\AbstractStorage;
use LetsCompose\Core\Storage\Exception\UnknownStorageResourceClassException;
use LetsCompose\Core\Storage\FileSystem\Adapter\FileStorageActionAdapter;
use LetsCompose\Core\Storage\FileSystem\Adapter\FileStorageAdapterFirst;
use LetsCompose\Core\Storage\FileSystem\Resource\File;
use LetsCompose\Core\Storage\FileSystem\Resource\FileInterface;
use LetsCompose\Core\Storage\Resource\ResourceInterface;
use LetsCompose\Core\Storage\Exception\PathNotFoundException;
use LetsCompose\Core\Storage\ResourceStorageInterface;
use LetsCompose\Core\Tools\ExceptionHelper;
use LetsCompose\Core\Tools\Storage\Path;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class LocalStorage extends AbstractStorage implements LocalResourceStorageInterface
{
    /**
     * @throws UnknownStorageResourceClassException
     * @throws ExceptionInterface
     */
    public function __construct(string $rootPath)
    {
        $this->setRootPath($rootPath);
        $adapters = [
            new FileStorageActionAdapter($this)
        ];
        $this->setResourceAdapters($adapters);
    }

    /**
     * @throws UnknownStorageResourceClassException
     * @throws ExceptionInterface
     */
    public function initFile(string $path): FileInterface
    {
        $file = parent::initResource(File::class);
        $file->setPath($path);
        return $file;
    }

    public function open(ResourceInterface $resource, ?string $mode = null): ResourceInterface
    {
        // TODO: Implement open() method.
    }

    public function read(ResourceInterface $resource): mixed
    {
        // TODO: Implement read() method.
    }

    public function write(ResourceInterface $resource, mixed $data): mixed
    {
        // TODO: Implement write() method.
    }

    public function close(ResourceInterface $resource): ResourceInterface
    {
        // TODO: Implement close() method.
    }

    public function remove(ResourceInterface $resource): ResourceInterface
    {
        // TODO: Implement remove() method.
    }

    public function isExists(ResourceInterface $resource): bool
    {
        // TODO: Implement isExists() method.
    }

    public function isReadable(ResourceInterface $resource): bool
    {
        // TODO: Implement isReadable() method.
    }

    public function isWritable(ResourceInterface $resource): bool
    {
        // TODO: Implement isWritable() method.
    }

    public function getFullPath(ResourceInterface $resource): string
    {
        // TODO: Implement getFullPath() method.
    }

    public function setRootPath(string $path): ResourceStorageInterface
    {
        if (false === Path::isAbsolute($path))
        {
            ExceptionHelper::create(new InvalidArgumentException())
                ->message('Invalid storage root path [%s]. Path must beginning with "/"', $path)
                ->throw()
            ;
        }

        $realPath = realpath($path);
        if (false === $realPath)
        {
            ExceptionHelper::create(new PathNotFoundException())
                ->message('Invalid storage root path [%s]. Path does not found "/"', $path)
                ->throw()
            ;
        }

        return parent::setRootPath($realPath);
    }


}