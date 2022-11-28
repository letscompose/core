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
use LetsCompose\Core\Exception\InterfaceNotAcceptedException;
use LetsCompose\Core\Storage\Config\ActionConfig;
use LetsCompose\Core\Storage\Config\ActionsConfigList;
use LetsCompose\Core\Storage\Config\ActionsConfigListInterface;
use LetsCompose\Core\Storage\Config\Resource\ResourceConfigInterface;
use LetsCompose\Core\Tools\ExceptionHelper;
use LetsCompose\Core\Tools\ObjectHelper;
use ReflectionException;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class ActionConfigFactory implements ActionConfigFactoryInterface
{
    /**
     * @throws ReflectionException
     * @throws ExceptionInterface
     */
    public static function create(ResourceConfigInterface $config): ActionsConfigListInterface
    {
        $actionsList = new ActionsConfigList();
        $storageClass = $config->getStorageClass();
        $resourceClass = $config->getClass();

        if (true  === ObjectHelper::isInterface($resourceClass))
        {
            ExceptionHelper::create(new InterfaceNotAcceptedException())
                ->message('Resource must be an valid object class, not an interface')
                ->throw();
        }

        $storageActionsNameList = self::getActionsNamesFromStorageInterfaces($storageClass);

        $resourceActionsNameSpace = $config->getActionNameSpace() ?? self::getResourceActionsNameSpace($resourceClass);

        foreach ($storageActionsNameList as $actionName)
        {
            $actionClass = sprintf('%s\\%sAction', $resourceActionsNameSpace, ucfirst($actionName));
            $actionConfig = new ActionConfig (
                $actionName,
                $resourceActionsNameSpace,
                $actionClass,
                $storageClass,
                $config->getClass()
            );

            $actionsList->addAction($actionConfig);
        }

        return $actionsList;
    }

    private static function getResourceActionsNameSpace(string $resourceClass): string
    {
        $actionNameSpace = self::getClassNameSpace($resourceClass);
        $resourceShortName = self::getClassShortName($resourceClass);
        return sprintf('%s\Action\%s', $actionNameSpace, $resourceShortName);
    }

    private static function getActionsNamesFromStorageInterfaces(string $storageClass): array
    {
        $interfaces = class_implements($storageClass);
        $implementedMethods = [];
        foreach($interfaces as $interface) {
            $implementedMethods = array_replace(get_class_methods($interface), $implementedMethods);
        }
        return $implementedMethods;
    }

    private static function getClassShortName(string $class): string
    {
        return substr(strrchr($class, '\\'), 1);
    }


    private static function getClassNameSpace(string $class): string
    {
        $shortName = self::getClassShortName($class);
        $nameSpace = substr($class, 0, strrpos($class, $shortName));
        return rtrim($nameSpace, '\\');
    }

    private static function isNameSpaceExists(string $nameSpace): bool
    {
        $nameSpace .= "\\";
        foreach (get_declared_classes() as $name) {
            if (str_starts_with($name, $nameSpace)) return true;
        }
        return false;
    }

}