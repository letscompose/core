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

use LetsCompose\Core\Storage\Resource\ResourceInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
interface ConfigInterface
{
    public function setStorageClass(string $storageClass): ConfigInterface;

    public function getStorageClass(): string;

    public function setRootPath(string $rootPath): ConfigInterface;

    public function getRootPath(): string;

    public function setResources(array $resources): ConfigInterface;

    public function getStorageResources(): array;
}