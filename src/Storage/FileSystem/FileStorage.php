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
use LetsCompose\Core\Storage\Exception\UnknownStorageResourceClassException;
use LetsCompose\Core\Storage\FileSystem\Adapter\DirectoryStorageActionAdapter;
use LetsCompose\Core\Storage\FileSystem\Adapter\FileStorageActionAdapter;
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
     * @throws UnknownStorageResourceClassException
     * @throws ExceptionInterface
     */
    public function __construct(string $rootPath)
    {
        $this->setRootPath($rootPath);
        $adapters = [
            new FileStorageActionAdapter($this),
            new DirectoryStorageActionAdapter($this),
        ];
        $this->setResourceAdapters($adapters);
    }

    public function initFile(string $path): FileInterface
    {
        $file = parent::initResource(File::class);
        $file->setPath($path);
        return $file;
    }

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

    public function create(ResourceInterface $resource): ResourceInterface
    {
        return $this->execute($resource::class, __FUNCTION__, $resource);
    }

    public function flush(FileInterface $file): bool
    {
        return $this->execute($file::class, __FUNCTION__, $file);
    }
}