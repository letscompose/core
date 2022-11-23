<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\Config;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
interface ConfigInterface
{
    /**
     * @param string $storageClass
     * @return ConfigInterface
     */
    public function setStorageClass(string $storageClass): ConfigInterface;

    /**
     * @return string
     */
    public function getStorageClass(): string;

    /**
     * @param string $rootPath
     * @return ConfigInterface
     */
    public function setRootPath(string $rootPath): ConfigInterface;

    /**
     * @return string
     */
    public function getRootPath(): string;
}