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

use LetsCompose\Core\Storage\Config\ConfigInterface;
use LetsCompose\Core\Storage\Config\ResourceConfig;
use LetsCompose\Core\Storage\FileSystem\Resource\FileInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class LocalStorageConfig implements LocalStorageConfigInterface
{

    private string $storageClass = LocalStorage::class;

    private string $rootPath;

    public function setStorageClass(string $storageClass): self
    {
        $this->storageClass = $storageClass;
        return $this;
    }

    public function getStorageClass(): string
    {
        return $this->storageClass;
    }

    public function setRootPath(string $rootPath): self
    {
        $this->rootPath = $rootPath;
        return $this;
    }

    public function getRootPath(): string
    {
        return $this->rootPath;
    }

    public function setResources(array $resources): ConfigInterface
    {
        // TODO: Implement setResources() method.
    }

    /**
     * @return ResourceConfig[]
     */
    public function getStorageResources(): array
    {
        return [
            new ResourceConfig(FileInterface::class)
        ];
    }
}