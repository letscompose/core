<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Tools;

use ReflectionException;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class ObjectHelper
{
    public static function hasInterface(string $interface, string $class): bool
    {
        $classInterfaces = class_implements($class, $interface);
        if (false === $classInterfaces)
        {
            return false;
        }
        return \in_array($interface, $classInterfaces);
    }

    /**
     * @throws ReflectionException
     */
    public static function isInterface(string $class): bool
    {
        $class = new \ReflectionClass($class);
        return $class->isInterface();
    }

    public static function getClassShortName(string $class): string
    {
        return substr(strrchr($class, '\\'), 1);
    }

    public static function getClassNameSpace(string $class): string
    {
        $shortName = self::getClassShortName($class);
        $nameSpace = substr($class, 0, strrpos($class, $shortName));
        return rtrim($nameSpace, '\\');
    }

    public static function isNameSpaceExists(string $nameSpace): bool
    {
        $nameSpace .= "\\";
        foreach (get_declared_classes() as $name) {
            if (str_starts_with($name, $nameSpace)) return true;
        }
        return false;
    }

//    private static function getActionsNamesFromStorageInterfaces(string $storageClass): array
//    {
//        $interfaces = class_implements($storageClass);
//        $implementedMethods = [];
//        foreach($interfaces as $interface) {
//            $implementedMethods = array_replace(get_class_methods($interface), $implementedMethods);
//        }
//        return $implementedMethods;
//    }

}