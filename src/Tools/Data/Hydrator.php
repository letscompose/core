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

use LetsCompose\Core\Tools\ArrayHelper;

class Hydrator
{
    private const OBJECT_SETTER_PREFIX = 'set';

    private static array $cache = [];

    private static array $instanceCache = [];

    public static function hydrate(object|string $object, array $params, bool $putIntoCache = false): object
    {
        if (true === empty($params))
        {
            return $object;
        }

        /**
         * if object is a string, is must be instanced
         * we don't want put into cache a new instance for many raisons
         * one of the reasons out of memory on bulk hydrate
         */
        if (true === is_string($object))
        {
            $object = new $object();
            $putIntoCache = false;
        }

        if ($putIntoCache)
        {
            /**
             * check if we have already hydrated object in cache,
             * return cached object if this case or continue otherwise
             */
            $paramsSign = json_encode($params);
            $objectInstanceHash = spl_object_hash($object);
            $signature = sha1($object::class.$objectInstanceHash.$paramsSign);

            if (isset(self::$cache[$signature]))
            {
                return self::$cache[$signature];
            }
        }

        /**
         * we get all object setters
         */
        $objectPropertySetters = self::getObjectPropertySetters($object);

        /**
         * remove setters for which $params doest not have a value
         */
        $objectPropertySetters = array_intersect_key($objectPropertySetters, $params);

        /**
         * call each found setter with corresponding value
         */
        foreach ($objectPropertySetters as $property => $setter)
        {
            call_user_func_array([$object, $setter], [$params[$property]]);
            /**
             * remove affected param
             */
            unset($params[$property]);
        }

        /**
         * we get all object properties
         */
        $properties = self::getObjectProperties($object);

        /**
         * remove properties which does not match with params
         */
        $properties = array_intersect_key($properties, $params);


        /**
         * affect rested properties with value of params
         */
        foreach ($properties as $property)
        {
            $object->{$property} = $params[$property];
            unset($params[$property]);
        }

        if($putIntoCache)
        {
            return self::$cache[$signature] = $object;
        }
        return $object;

    }

    /**
     * @return array<string, string>
     */
    private static function getObjectPropertySetters(object $object): array
    {
        if (isset(self::$cache[$object::class]))
        {
            return self::$cache[$object::class];
        }

        $result = [];
        $objectMethods = get_class_methods($object);
        $prefixLength = strlen(self::OBJECT_SETTER_PREFIX);

        foreach ($objectMethods as $objectMethod)
        {
            if (str_starts_with($objectMethod, self::OBJECT_SETTER_PREFIX))
            {
                $propertyName = substr($objectMethod, $prefixLength);
                $result[lcfirst($propertyName)] = $objectMethod;
            }
        }
        return self::$cache[$object::class] = $result;
    }

    /**
     * @return array<string, string>
     */
    private static function getObjectProperties(object $object): array
    {
        $objectProperties = array_keys(get_object_vars($object));
        return array_combine($objectProperties, $objectProperties);
    }

}