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

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\MustImplementException;
use LetsCompose\Core\Storage\Adapter\AdapterInterface;
use LetsCompose\Core\Storage\Config\Adapter\AdapterConfigInterface;
use LetsCompose\Core\Storage\Config\Storage\StorageConfigInterface;
use LetsCompose\Core\Storage\Exception\InvalidStorageClassException;
use LetsCompose\Core\Storage\Exception\ResourceAlreadyManagedException;
use LetsCompose\Core\Storage\Exception\UnknownStorageClassException;
use LetsCompose\Core\Storage\Exception\UnknownStorageResourceClassException;
use LetsCompose\Core\Storage\ResourceStorageInterface;
use LetsCompose\Core\Storage\StorageInterface;
use LetsCompose\Core\Tools\ExceptionHelper;
use LetsCompose\Core\Tools\ObjectHelper;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class StorageFactory implements StorageFactoryInterface
{
    /**
     * @throws ResourceAlreadyManagedException
     * @throws MustImplementException
     * @throws UnknownStorageResourceClassException
     * @throws ExceptionInterface
     */
    public static function create(StorageConfigInterface $config): ResourceStorageInterface
    {
        $storageClass = $config->getClass();
        if (false === class_exists($storageClass))
        {
            ExceptionHelper::create(new UnknownStorageClassException())
                ->message('You try to create storage from unknown storage class [%s]', $storageClass)
                ->throw()
            ;
        }

        if (false === ObjectHelper::hasInterface($storageClass, ResourceStorageInterface::class))
        {
            ExceptionHelper::create(new InvalidStorageClassException())
                ->message('You try to create storage from class [%s] which not implement Storage Interface [%s]', $storageClass, ResourceStorageInterface::class)
                ->throw()
            ;
        }

        /**
         * @var StorageInterface $storage
         */
        $storage = new $storageClass($config);

        $adapters = [];
        foreach ($config->getAdapterConfigList() as $adapterConfig)
        {
            self::throwExceptionIfResourceAlreadySupported($adapterConfig, $adapters);
            $adapter = AdapterFactory::create($adapterConfig);
            $adapter->setStorage($storage);
            $adapters[] = $adapter;
        }

        $storage->setResourceAdapters($adapters);





//        $storageResources = $config->getStorageResources();
//        $actions = [];
//
//        foreach ($storageResources as $resourceConfig)
//        {
//            $resourceConfig->setStorageClass($storageClass);
//            $resourceActions = ActionConfigFactory::create($resourceConfig);
//            $actions[$resourceConfig->getClass()] = $resourceActions;
//        }
//
//        /**
//         * @var AbstractCompositeStorage $storage
//         */
//        $storage = new $storageClass($config);
//        $storage->setActionConfigList($actions);
//        $storage->setActionHandler(new ActionHandler());

        return $storage;
    }

    /**
     * @param AdapterConfigInterface $adapterConfig
     * @param AdapterInterface[] $adapters
     * @return void
     * @throws ResourceAlreadyManagedException
     * @throws ExceptionInterface
     */
    private static function throwExceptionIfResourceAlreadySupported(AdapterConfigInterface $adapterConfig, array $adapters): void
    {
        foreach ($adapters as $adapter)
        {
            foreach ($adapterConfig->getResourceConfigList() as $resourceConfig)
            {
                $resourceClass = $resourceConfig->getClass();
                if ($adapter->hasResourceConfig($resourceClass)) {
                    ExceptionHelper::create(new ResourceAlreadyManagedException())
                        ->message('You try to create an adapter [%s] for resource [%s] supported by an other adapter [%s]. Check your storage config', $adapterConfig->getClass(), $resourceClass, $adapter::class)
                        ->throw();
                }
            }
        }
    }

}