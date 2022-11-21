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

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class FileSystemStorageStorageConfig implements FileSystemStorageConfigInterface
{
    /**
     * @var string
     */
    private string $storageClass = FileSystemStorage::class;

    /**
     * @var string
     */
    private string $rootPath;

    /**
     * @param string $storageClass
     * @return $this
     */
    public function setStorageClass(string $storageClass): self
    {
        $this->storageClass = $storageClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getStorageClass(): string
    {
        return $this->storageClass;
    }

    /**
     * @param string $rootPath
     * @return FileSystemStorageStorageConfig
     */
    public function setRootPath(string $rootPath): self
    {
        $this->rootPath = $rootPath;
        return $this;
    }

    /**
     * @return string
     */
    public function getRootPath(): string
    {
        return $this->rootPath;
    }


}