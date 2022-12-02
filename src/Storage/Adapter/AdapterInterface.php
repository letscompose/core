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
interface AdapterInterface
{
    public function __construct(StorageInterface $storage);

    public function setStorage(ResourceStorageInterface $storage): self;

    public function getStorage(): ResourceStorageInterface;

    public function isResourceSupported(string $resourceClass): bool;

    public function getSupportedResource(): string;

    public function initResource(string $resourceClass): ResourceInterface;

    public function execute(string $action, ...$params);

}