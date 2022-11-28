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

use Generator;
use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\InvalidArgumentException;
use LetsCompose\Core\Storage\AbstractResourceStorage;
use LetsCompose\Core\Storage\Exception\ActionNotFoundException;
use LetsCompose\Core\Storage\Exception\ActionNotImplementedException;
use LetsCompose\Core\Storage\Exception\UnsupportedStorageResourceException;
use LetsCompose\Core\Storage\FileSystem\Adapter\FileStorageAdapter;
use LetsCompose\Core\Storage\FileSystem\Resource\File;
use LetsCompose\Core\Storage\FileSystem\Resource\FileInterface;
use LetsCompose\Core\Storage\Resource\ResourceInterface;
use LetsCompose\Core\Storage\Exception\PathNotFoundException;
use LetsCompose\Core\Tools\ExceptionHelper;
use LetsCompose\Core\Tools\Storage\Path;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class LocalStorage extends AbstractResourceStorage implements LocalResourceStorageInterface
{
    /**
     * @var array
     */
    private array $resourceAdapters = [];

    /**
     * @param LocalStorageConfig $config
     * @throws ExceptionInterface
     */
    public function __construct(LocalStorageConfig $config)
    {
        $this->setRootPath($config->getRootPath());
        $this->resourceAdapters = [
            File::class => new FileStorageAdapter($this)
        ];

        $this->supportedResources = array_keys($this->resourceAdapters);
    }

    /**
     * @param ResourceInterface $resource
     * @param string|null $mode
     * @return FileInterface
     * @throws ExceptionInterface
     */
    public function open(ResourceInterface $resource, ?string $mode = self::OPEN_MODE_READ): FileInterface
    {
        $this->exceptionIfResourceNotSupported($resource);
        return $this->resourceAdapters[$resource::class]->open($resource, $mode);
    }

    /**
     * @param ResourceInterface $resource
     * @param int $chunkSize
     * @return mixed
     * @throws ExceptionInterface
     * @throws ActionNotFoundException
     * @throws ActionNotImplementedException
     */
    public function read(ResourceInterface $resource, int $chunkSize = 1024): mixed
    {
        return $this->execute(__FUNCTION__, $resource, $chunkSize);
    }

    public function readLine(FileInterface $file): Generator
    {
        $this->exceptionIfResourceNotSupported($file);
        return $this->resourceAdapters[$file::class]->readLine($file);
    }

    public function remove(ResourceInterface $resource): ResourceInterface
    {
        // TODO: Implement remove() method.
    }

    public function close(ResourceInterface $resource): ResourceInterface
    {
        $this->exceptionIfResourceNotSupported($resource);
        return $this->resourceAdapters[$resource::class]->close($resource);
    }

    public function isExists(ResourceInterface $resource): bool
    {
        $this->exceptionIfResourceNotSupported($resource);
        return $this->resourceAdapters[$resource::class]->isExists($resource);
    }

    public function isReadable(ResourceInterface $resource): bool
    {
        $this->exceptionIfResourceNotSupported($resource);
        return $this->resourceAdapters[$resource::class]->isReadable($resource);
    }

    public function isWritable(ResourceInterface $resource): bool
    {
        $this->exceptionIfResourceNotSupported($resource);
        return $this->resourceAdapters[$resource::class]->isWritable($resource);
    }


    public function setRootPath(string $rootPath): self
    {
        if (false === Path::isAbsolute($rootPath))
        {
            ExceptionHelper::create(new InvalidArgumentException())
                ->message('Invalid storage root path [%s]. Path must beginning with "/"', $rootPath)
                ->throw()
                ;
        }

        $realPath = realpath($rootPath);
        if (false === $realPath)
        {
            ExceptionHelper::create(new PathNotFoundException())
                ->message('Invalid storage root path [%s]. Path does not found "/"', $rootPath)
                ->throw()
            ;
        }

        return parent::setRootPath($realPath);
    }

    public function getFullPath(ResourceInterface $resource): string
    {
        $this->exceptionIfResourceNotSupported($resource);
        return $this->resourceAdapters[$resource::class]->getFullPath($resource);
    }

    public function createFileResource(string $path): FileInterface
    {
        return $this->resourceAdapters[File::class]->initResource($path);
    }

    public function write(ResourceInterface $resource, mixed $data): mixed
    {
        return $this->execute($resource, __FUNCTION__);
    }

    private function exceptionIfResourceNotSupported(ResourceInterface $resource)
    {
        if (false === $this->isResourceSupported($resource))
        {
            ExceptionHelper::create(new UnsupportedStorageResourceException())
                ->message('Unsupported storage resource [%s], resource must be one of theses [%s]', $resource::class, implode(',', $this->supportedResources))
                ->throw();
        }
    }

}