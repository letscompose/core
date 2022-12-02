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

use LetsCompose\Core\Storage\Resource\ResourceInterface;
use LetsCompose\Core\Storage\ResourceStorageInterface;
use LetsCompose\Core\Storage\StorageInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
abstract class AbstractAdapter implements AdapterInterface
{
    protected ResourceStorageInterface $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->setStorage($storage);
    }

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