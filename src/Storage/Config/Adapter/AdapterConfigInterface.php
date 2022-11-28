<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\Config\Adapter;

use LetsCompose\Core\Storage\Config\ConfigInterface;
use LetsCompose\Core\Storage\Config\Resource\ResourceConfigInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
interface AdapterConfigInterface extends ConfigInterface
{
    /**
     * @param ResourceConfigInterface[] $configList
     */
    public function setResourceConfigList(array $configList);

    /**
     * @return ResourceConfigInterface[]
     */
    public function getResourceConfigList(): array;

    public function addResourceConfig(ResourceConfigInterface $config);

    public function hasResourceConfig(ResourceConfigInterface $config): bool;
}