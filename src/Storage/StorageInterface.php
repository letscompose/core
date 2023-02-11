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


use LetsCompose\Core\Storage\Adapter\AdapterInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
interface StorageInterface extends ResourceStorageInterface
{

    public function __construct(string $rootPath);

    /**
     * @param AdapterInterface[] $resourceAdapters
     */
    public function setResourceAdapters(array $resourceAdapters): self;

    public function addResourceAdapter(AdapterInterface $adapter);

    /**
     * @return AdapterInterface[]
     */
    public function getResourceAdapters(): array;

    public function getResourceAdapter(string $resourceClass): AdapterInterface;
}