<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\Adapter;


use LetsCompose\Core\Storage\Config\Resource\ResourceConfigInterface;
use LetsCompose\Core\Storage\Exception\ConfigAlreadyDefinedException;
use LetsCompose\Core\Storage\Exception\UnknownStorageResourceClassException;
use LetsCompose\Core\Storage\Resource\ResourceInterface;
use LetsCompose\Core\Storage\ResourceStorageInterface;
use LetsCompose\Core\Tools\ExceptionHelper;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
abstract class AbstractAdapter implements AdapterInterface
{
    private ResourceStorageInterface $storage;

    /**
     * @var ResourceConfigInterface[]
     */
    private array $resourceConfigList = [];

    /**
     * @return ResourceStorageInterface
     */
    public function getStorage(): ResourceStorageInterface
    {
        return $this->storage;
    }

    /**
     * @param ResourceStorageInterface $storage
     * @return AbstractAdapter
     */
    public function setStorage(ResourceStorageInterface $storage): self
    {
        $this->storage = $storage;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setResourceConfigList(array $resourceConfigList): self
    {
        $this->resourceConfigList = $resourceConfigList;
        foreach ($resourceConfigList as $resourceConfig)
        {
            $this->addResourceConfig($resourceConfig);
        }
        return $this;
    }

    public function addResourceConfig(ResourceConfigInterface $resourceConfig): self
    {
        if (true === $this->hasResourceConfig($resourceConfig->getClass()))
        {
            ExceptionHelper::create(new ConfigAlreadyDefinedException())
                ->message('Resource config already defined for [%s] at storage adapter [%s]', $resourceConfig->getClass(), $this::class)
                ->throw()
            ;
        }
        $this->resourceConfigList[$resourceConfig->getClass()] = $resourceConfig;
        return $this;
    }

    public function getResourceConfig(string $resourceClass): ResourceConfigInterface
    {
        if (!$this->hasResourceConfig($resourceClass))
        {
            ExceptionHelper::create(new UnknownStorageResourceClassException())
                ->message('You try to get unknown resource config [%s] at storage adapter [%s]', $resourceClass, $this::class)
                ->throw()
            ;
        }
        return $this->resourceConfigList[$resourceClass];
    }

    /**
     * @param string $resourceClass
     * @return bool
     */
    public function hasResourceConfig(string $resourceClass): bool
    {
        return \array_key_exists($resourceClass, $this->resourceConfigList);
    }

    /**
     * @inheritDoc
     */
    public function getResourceConfigList(): array
    {
        return $this->resourceConfigList;
    }


    public function isResourceSupported(string $resourceClass): bool
    {
        return $this->hasResourceConfig($resourceClass);
    }

    public function initResource(string $resourceClass): ResourceInterface
    {
        /**
         * @var ResourceInterface $resource;
         */
        $resource = new $resourceClass;
        $resource->setStorageClass($this->storage::class);
        return $resource;
    }

    public function execute(string $action, ...$params)
    {
        return $this->{$action}(...$params);
    }


}