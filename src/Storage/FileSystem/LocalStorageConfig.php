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

use LetsCompose\Core\Storage\Config\Adapter\AdapterConfig;
use LetsCompose\Core\Storage\Config\Resource\ResourceConfig;
use LetsCompose\Core\Storage\Config\Storage\AbstractStorageConfig;
use LetsCompose\Core\Storage\FileSystem\Adapter\FileStorageAdapterTest;
use LetsCompose\Core\Storage\FileSystem\Resource\File;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class LocalStorageConfig extends AbstractStorageConfig implements LocalStorageConfigInterface
{
    public function __construct()
    {
        $this->setClass(LocalStorageTest::class);

        $adapterConfig = new AdapterConfig();
        $adapterConfig->setClass(FileStorageAdapterTest::class);

        $resourceConfig = new ResourceConfig(File::class);
        $adapterConfig->addResourceConfig($resourceConfig);

        $this->addAdapterConfig($adapterConfig);
    }
}