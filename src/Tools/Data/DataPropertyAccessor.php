<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Tools\Data;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\InvalidArgumentException;
use LetsCompose\Core\Tools\ExceptionHelper;

class DataPropertyAccessor
{
    private static int $iterationCount = 0;

    private static array $cache = [];

    private const OBJECT_METHOD_PREFIX_GET = 'get';
    private const OBJECT_METHOD_PREFIX_IS = 'is';
    private const OBJECT_METHOD_PREFIX_HAS = 'has';
    private const OBJECT_METHOD_PREFIX_LIST = [
        self::OBJECT_METHOD_PREFIX_GET,
        self::OBJECT_METHOD_PREFIX_IS,
        self::OBJECT_METHOD_PREFIX_HAS,
    ];

    /**
     * get property of array or object at path, where path is dot delimited string
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     */
    public static function getPropertyAtPath(string $path, array|object $target): mixed
    {
        if (!$path)
        {
            ExceptionHelper::create(new InvalidArgumentException())
                ->setMessage('parameter $path must be not empty string')
                ->throw();
        }

        if (is_object($target))
        {
            $target = self::objectToArray($target);
        }

        if (!$target)
        {
            ExceptionHelper::create(new InvalidArgumentException())
                ->setMessage('parameter $target must be not empty array or object')
                ->throw();
        }

        if (false === array_key_exists($path, self::$cache))
        {
            $parts = explode('.', $path);
            $nullSafe = [];
            $cleanedPath = [];

            foreach ($parts as $i => $part)
            {
                $position = strpos($part, '?');
                if (false === $position)
                {
                    $cleanedPath[] = $part;
                    continue;
                }
                $part = substr_replace($part, '', $position, 1);
                $nullSafe[$i] = $i;
                $cleanedPath[] = $part;
            }

            self::$cache[$path] = [
                $cleanedPath,
                $nullSafe
            ];
        }

        [$cleanedPath, $nullSafe] = self::$cache[$path];

        foreach ($cleanedPath as $i => $part) {
            try {
                $target = self::getValue($target, $part);
                self::$iterationCount++;
                if (self::$iterationCount >= 5000) {
                    gc_collect_cycles();
                    self::$iterationCount = 0;
                }
                if (null === $target && in_array($i, $nullSafe))
                {
                    return null;
                }
            } catch (InvalidArgumentException $exception) {
                ExceptionHelper::create(new InvalidArgumentException())
                    ->setMessage('not readable data property [%s] at path [%s]. %s', $part, $path, $exception->getMessage())
                    ->throw();
            }
        }

        return $target;
    }

    /**
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     */
    private static function getValue(object|array $target, string $path): mixed
    {
        if (is_object($target))
        {
            $target = self::objectToArray($target);
        }

        if (!is_array($target))
        {
            ExceptionHelper::create(new InvalidArgumentException())
                ->setMessage('$target not an array')
                ->throw();
        }

        if (!array_key_exists($path, $target))
        {
            ExceptionHelper::create(new InvalidArgumentException())
                ->setMessage('Data property at path [%s] doest not exist', $path)
                ->throw();
        }

        return $target[$path];
    }


    public static function objectToArray(object $object, array $excludeMethods = []): array
    {
        if ($object instanceof \DateTimeInterface || $object instanceof \DateTimeZone)
        {
            $object = \json_encode($object);
            return \json_decode($object, true);
        }

        $getterResult = [
            self::OBJECT_METHOD_PREFIX_IS => [],
            self::OBJECT_METHOD_PREFIX_HAS => [],
            self::OBJECT_METHOD_PREFIX_GET => []
        ];

        /**
         * construct object methods list.
         * fist try to get cached result
         */
        if (!$finalMethodsList = self::$cache[$object::class] ?? [])
        {
            /**
             * function detect if passed method name is a getter, then return couple of
             * propertyName and getterPrefix and false otherwise
             */
            $getterDetect = function (string $getterName) {
                foreach (self::OBJECT_METHOD_PREFIX_LIST as $getterPrefix) {
                    if (true === str_starts_with($getterName, $getterPrefix)) {
                        $propName = lcfirst(substr($getterName, strlen($getterPrefix)));
                        return [
                            $propName,
                            $getterPrefix
                        ];
                    }
                }
                return false;
            };

            /**
             * construct object methods list.
             */
            $objectMethods = get_class_methods($object);
            if (false === empty($excludeMethods))
            {
                $objectMethods = array_diff($objectMethods, $excludeMethods);
            }
            $finalMethodsList = [];
            foreach ($objectMethods as $methodName) {
                $detectResult = $getterDetect($methodName);
                if ($detectResult) {
                    $finalMethodsList[$methodName] = $detectResult;
                }
            }
        }

        /**
         *
         */
        foreach ($finalMethodsList as $methodName => $detectResult)
        {
            [$propName, $getterPrefix] = $detectResult;
            try {
                $getterResult[$getterPrefix][$propName] = $object->{$methodName}();
            } catch (\ArgumentCountError $e) {
                unset($finalMethodsList[$methodName]);
            }
        }

        self::$cache[$object::class] = $finalMethodsList;


        $objectProperties = get_object_vars($object);
        /*
         * return result
         * always prefer [get] populated properties over [has] and [is]
         */
        return array_replace($objectProperties, $getterResult[self::OBJECT_METHOD_PREFIX_HAS], $getterResult[self::OBJECT_METHOD_PREFIX_IS], $getterResult[self::OBJECT_METHOD_PREFIX_GET]);
    }
}