<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\Factory;

use LetsCompose\Core\Storage\Config\ConfigInterface;
use LetsCompose\Core\Storage\StorageInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
interface StorageFactoryInterface
{
    /**
     * @param ConfigInterface $config
     * @return StorageInterface
     */
    public static function create(ConfigInterface $config): StorageInterface;
}