<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\Config\Storage;

use LetsCompose\Core\Storage\Adapter\AdapterInterface;
use LetsCompose\Core\Storage\Config\Adapter\AdapterConfigInterface;
use LetsCompose\Core\Storage\Config\ConfigInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
interface StorageConfigInterface extends ConfigInterface
{
    public function setRootPath(string $rootPath): self;

    public function getRootPath(): string;

    /**
     * @param AdapterConfigInterface[] $configList
     */
    public function setAdapterConfigList(array $configList): self;

    /**
     * @return AdapterConfigInterface[]
     */
    public function getAdapterConfigList(): array;

    public function addAdapterConfig(AdapterConfigInterface $adapter): self;
}