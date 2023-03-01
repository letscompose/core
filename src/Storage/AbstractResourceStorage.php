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
use LetsCompose\Core\Storage\Exception\ResourceAlreadyManagedException;
use LetsCompose\Core\Storage\Exception\UnknownStorageResourceClassException;
use LetsCompose\Core\Storage\Resource\ResourceInterface;
use LetsCompose\Core\Tools\ExceptionHelper;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
abstract class AbstractResourceStorage implements StorageInterface
{
    /**
     * @var AdapterInterface[]
     */
    protected array $resourceAdapters = [];

    private string $rootPath;


    public function getRootPath(): string
    {
        return $this->rootPath;
    }

    public function setRootPath(string $path): ResourceStorageInterface
    {
        $this->rootPath = $path;

        return $this;
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
     * @throws UnknownStorageResourceClassException
     * @throws ExceptionInterface
     */
    public function setResourceAdapters(array $resourceAdapters): AbstractResourceStorage
    {
        $this->resourceAdapters = [];
        foreach ($resourceAdapters as $adapter)
        {
            $this->addResourceAdapter($adapter);
        }
        return $this;
    }

    /**
     * @throws UnknownStorageResourceClassException
     * @throws ExceptionInterface
     */
    public function addResourceAdapter(AdapterInterface $adapter)
    {
        $resourceClass = $adapter->getSupportedResource();

        if ($this->isResourceSupported($resourceClass))
        {
            $resourceAdapter = $this->getResourceAdapter($resourceClass);
            ExceptionHelper::create(new ResourceAlreadyManagedException())
                ->setMessage('You try to add an adapter [%s] for resource [%s] supported by an other adapter [%s]. Check your storage config', $adapter::class, $resourceClass, $resourceAdapter::class)
                ->throw();
        }
        $adapter->setStorage($this);
        $this->resourceAdapters[$adapter::class] = $adapter;
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
            ->setMessage('Not supported storage resource [%s]', $resourceClass)
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