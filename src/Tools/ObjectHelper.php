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
    public static function hasInterface(string $class, string $interface): bool
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

}