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
use LetsCompose\Core\Storage\Config\ResourceConfigInterface;
use LetsCompose\Core\Storage\Exception\InvalidStorageClassException;
use LetsCompose\Core\Storage\Exception\UnknownStorageClassException;
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

        /***
         * @var ResourceConfigInterface[] $storageResources
         */
        $storageResources = $config->getStorageResources();
        $actions = [];
        $actionNameSpace = null;

        foreach ($storageResources as $resourceConfig)
        {
            $resourceActions = ;

//            $actionNameSpace = self::getResourceActionsNameSpace($resourceClass);
//            $actionNames = self::getActionsNamesFromStorageInterfaces($storageClass);
//            foreach ($actionNames as $actionName)
//            {
//                $actions[$resourceClass][] = sprintf('%s\\%sAction', $actionNameSpace, ucfirst($actionName));
//            }
        }

        dump($actions);
        die;
        return new $storageClass($config);
    }

    /**
     * @param string $resourceClass
     * @return string
     */
    private static function getResourceActionsNameSpace(string $resourceClass): string
    {
            $actionNameSpace = self::getClassNameSpace($resourceClass);
            $resourceShortName = self::getClassShortName($resourceClass);
            return sprintf('%s\Action\%s', $actionNameSpace, $resourceShortName);
    }

    /**
     * @param string $storageClass
     * @return string[]
     */
    private static function getActionsNamesFromStorageInterfaces(string $storageClass): array
    {
        $interfaces = class_implements($storageClass);
        $implementedMethods = [];
        foreach($interfaces as $interface) {
            $implementedMethods = array_replace(get_class_methods($interface), $implementedMethods);
        }
        return $implementedMethods;
    }

    /**
     * @param string $class
     * @return string
     */
    private static function getClassShortName(string $class): string
    {
        return substr(strrchr($class, '\\'), 1);
    }

    /**
     * @param string $class
     * @return string
     */
    private static function getClassNameSpace(string $class): string
    {
        $shortName = self::getClassShortName($class);
        $nameSpace = substr($class, 0, strrpos($class, $shortName));
        return rtrim($nameSpace, '\\');
    }
}