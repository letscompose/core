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
use LetsCompose\Core\Exception\MustImplementException;
use LetsCompose\Core\Storage\Exception\UnknownStorageResourceClassException;
use LetsCompose\Core\Storage\FileSystem\Local\Adapter\DirectoryStorageActionAdapter;
use LetsCompose\Core\Storage\FileSystem\Local\Adapter\FileStorageActionAdapter;
use LetsCompose\Core\Storage\FileSystem\Resource\Directory;
use LetsCompose\Core\Storage\FileSystem\Resource\DirectoryInterface;
use LetsCompose\Core\Storage\FileSystem\Resource\File;
use LetsCompose\Core\Storage\FileSystem\Resource\FileInterface;
use LetsCompose\Core\Storage\Resource\ResourceInterface;
use LetsCompose\Core\Storage\ResourceStorage;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class FileStorage extends ResourceStorage implements FileStorageInterface
{

    /**
     * @throws MustImplementException
     * @throws ExceptionInterface
     */
    public function getResourceAdapters(): array
    {
        return [
            new FileStorageActionAdapter($this),
            new DirectoryStorageActionAdapter($this),
        ];
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

    /**
     * @throws UnknownStorageResourceClassException
     * @throws ExceptionInterface
     */
    public function initDirectory(string $path): DirectoryInterface
    {
        $directory = parent::initResource(Directory::class);
        $directory->setPath($path);
        return $directory;
    }

    public function createDirectory(DirectoryInterface $directory): DirectoryInterface
    {
        return $this->create($directory);
    }

    /**
     * @throws UnknownStorageResourceClassException
     * @throws ExceptionInterface
     */
    public function create(ResourceInterface $resource): ResourceInterface
    {
        return $this->execute($resource::class, __FUNCTION__, $resource);
    }

    /**
     * @throws UnknownStorageResourceClassException
     * @throws ExceptionInterface
     */
    public function flush(FileInterface $file): bool
    {
        return $this->execute($file::class, __FUNCTION__, $file);
    }
}