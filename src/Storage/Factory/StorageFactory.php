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
use LetsCompose\Core\Storage\FileNotFoundException;
use LetsCompose\Core\Storage\InvalidStorageClassException;
use LetsCompose\Core\Storage\UnknownStorageClassException;
use LetsCompose\Core\Storage\StorageInterface;
use LetsCompose\Core\Tools\ExceptionHelper;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class StorageFactory implements StorageFactoryInterface
{
    /**
     * @inheritDoc
     */
    public static function create(ConfigInterface $config): StorageInterface
    {
        $storageClass = $config->getStorageClass();
        if (false === class_exists($storageClass))
        {
            ExceptionHelper::create(new UnknownStorageClassException())
                ->message('You try to create storage from unknown storage class [%s]', $storageClass)
                ->throw()
            ;
        }

        $interfaces = class_implements($storageClass);
        if (!\in_array(StorageInterface::class, $interfaces))
        {
            ExceptionHelper::create(new InvalidStorageClassException())
                ->message('You try to create storage from class [%s] which not implement Storage Interface [%s]', $storageClass, StorageInterface::class)
                ->throw()
            ;
        }

        return new $storageClass($config);
    }
}