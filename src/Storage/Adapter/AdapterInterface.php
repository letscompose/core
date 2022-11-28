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
use LetsCompose\Core\Storage\Resource\ResourceInterface;
use LetsCompose\Core\Storage\ResourceStorageInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
interface AdapterInterface
{

    public function setStorage(ResourceStorageInterface $storage): self;

    public function getStorage(): ResourceStorageInterface;

    /**
     * @param ResourceConfigInterface[] $resourceConfigList
     */
    public function setResourceConfigList(array $resourceConfigList): self;

    /**
     * @return ResourceConfigInterface[]
     */
    public function getResourceConfigList(): array;

    public function addResourceConfig(ResourceConfigInterface $resourceConfig): self;

    public function hasResourceConfig(string $resourceClass): bool;

    public function getResourceConfig(string $resourceClass): ResourceConfigInterface;

    public function isResourceSupported(string $resourceClass): bool;

    public function initResource(string $resourceClass): ResourceInterface;

    public function execute(string $action, ...$params);

}