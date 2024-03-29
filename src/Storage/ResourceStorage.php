<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\InvalidArgumentException;
use LetsCompose\Core\Storage\Exception\UnknownStorageResourceClassException;
use LetsCompose\Core\Storage\FileSystem\Enum\FileOpenModeEnum;
use LetsCompose\Core\Storage\FileSystem\Resource\FileInterface;
use LetsCompose\Core\Storage\Resource\ResourceInfoInterface;
use LetsCompose\Core\Storage\Resource\ResourceInterface;
use LetsCompose\Core\Storage\Exception\PathNotFoundException;
use LetsCompose\Core\Tools\ExceptionHelper;
use LetsCompose\Core\Tools\Storage\PathHelper;
use UnitEnum;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class ResourceStorage extends AbstractResourceStorage
{
    /**
     * @throws UnknownStorageResourceClassException
     * @throws ExceptionInterface
     */
    public function __construct(string $rootPath)
    {
        $this->setRootPath($rootPath);

        $adapters = $this->getResourceAdapters();

        $this->setResourceAdapters($adapters);
    }

    public function create(ResourceInterface $resource): ResourceInterface
    {
        return $this->execute($resource::class, __FUNCTION__, $resource);
    }

    public function open(ResourceInterface $resource, UnitEnum $mode = FileOpenModeEnum::READ): ResourceInterface
    {
        return $this->execute($resource::class, __FUNCTION__, $resource, $mode);
    }

    public function read(ResourceInterface $resource, int $chunkSize = 1024): mixed
    {
        return $this->execute($resource::class, __FUNCTION__, $resource, $chunkSize);
    }

    public function write(ResourceInterface $resource, mixed $data, int $length = null): mixed
    {
        return $this->execute($resource::class, __FUNCTION__, $data, $length);
    }

    public function close(ResourceInterface $resource): ResourceInterface
    {
        return $this->execute($resource::class, __FUNCTION__, $resource);
    }

    public function remove(ResourceInterface $resource): ResourceInterface
    {
        return $this->execute($resource::class, __FUNCTION__, $resource);
    }

    public function isExists(ResourceInterface $resource): bool
    {
        return $this->execute($resource::class, __FUNCTION__, $resource);
    }

    public function isReadable(ResourceInterface $resource): bool
    {
        return $this->execute($resource::class, __FUNCTION__, $resource);
    }

    public function isWritable(ResourceInterface $resource): bool
    {
        return $this->execute($resource::class, __FUNCTION__, $resource);
    }

    public function getFullPath(ResourceInterface $resource): string
    {
        $path = sprintf('%s/%s', $this->getRootPath(), $resource->getPath());

        return PathHelper::normalize($path);
    }

    public function getInfo(ResourceInterface $resource): ResourceInfoInterface
    {
        return $this->execute($resource::class, __FUNCTION__, $resource);
    }

    public function readLine(FileInterface $file): mixed
    {
        return $this->execute($file::class, __FUNCTION__, $file);
    }

    public function flush(FileInterface $file): bool
    {
        return $this->execute($file::class, __FUNCTION__, $file);
    }

    public function setRootPath(string $path): ResourceStorageInterface
    {
        if (false === PathHelper::isAbsolute($path))
        {
            ExceptionHelper::create(new InvalidArgumentException())
                ->setMessage('Invalid storage root path [%s]. Path must beginning with "/"', $path)
                ->throw()
            ;
        }

        $realPath = realpath($path);
        if (false === $realPath)
        {
            ExceptionHelper::create(new PathNotFoundException())
                ->setMessage('Invalid storage root path [%s]. Path does not found "/"', $path)
                ->throw()
            ;
        }

        return parent::setRootPath($realPath);
    }
}