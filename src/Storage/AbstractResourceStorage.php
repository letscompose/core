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
use LetsCompose\Core\Storage\Adapter\AdapterInterface;
use LetsCompose\Core\Storage\Config\Storage\StorageConfigInterface;
use LetsCompose\Core\Storage\Exception\UnknownStorageResourceClassException;
use LetsCompose\Core\Storage\Resource\ResourceInterface;
use LetsCompose\Core\Tools\ExceptionHelper;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
abstract class AbstractResourceStorage implements StorageInterface
{
    protected string $rootPath;

    protected StorageConfigInterface $config;

    /**
     * @var AdapterInterface[]
     */
    protected array $resourceAdapters = [];

    public function getRootPath(): string
    {
        return $this->rootPath;
    }

    public function setRootPath(string $path): ResourceStorageInterface
    {
        $this->rootPath = $path;
        return $this;
    }

    public function setConfig(StorageConfigInterface $config): ResourceStorageInterface
    {
        $this->config = $config;
        return $this;
    }

    public function getConfig(): StorageConfigInterface
    {
        return $this->config;
    }

    /**
     * @throws UnknownStorageResourceClassException
     * @throws ExceptionInterface
     */
    public function initResource(string $resourceClass): ResourceInterface
    {
        return $this->execute($resourceClass, __FUNCTION__, $resourceClass);
    }

    public function isResourceSupported(string $resourceClass): bool
    {
        foreach ($this->resourceAdapters as $adapter)
        {
            if ($adapter->isResourceSupported($resourceClass))
            {
                return true;
            }
        }
        return false;
    }

    /**
     * @return array
     */
    public function getResourceAdapters(): array
    {
        return $this->resourceAdapters;
    }

    /**
     * @param array $resourceAdapters
     * @return AbstractResourceStorage
     */
    public function setResourceAdapters(array $resourceAdapters): AbstractResourceStorage
    {
        $this->resourceAdapters = $resourceAdapters;
        return $this;
    }

    /**
     * @throws UnknownStorageResourceClassException
     * @throws ExceptionInterface
     */
    public function getResourceAdapter(string $resourceClass): AdapterInterface
    {
        foreach ($this->resourceAdapters as $adapter)
        {
            if ($adapter->isResourceSupported($resourceClass))
            {
                return $adapter;
            }
        }
        ExceptionHelper::create(new UnknownStorageResourceClassException())
            ->message('Not supported storage resource [%s]', $resourceClass)
            ->throw()
        ;
    }

    /**
     * @throws UnknownStorageResourceClassException
     * @throws ExceptionInterface
     */
    protected function execute(string $resourceClass, string $method, ...$params): mixed
    {
        return $this->getResourceAdapter($resourceClass)->execute($method, ...$params );
    }
}