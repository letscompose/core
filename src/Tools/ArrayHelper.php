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

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\InvalidArgumentException;

class ArrayHelper
{
    private static array $cache = [];

    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    public static function snakeKeysToCamelCase(array $params): array
    {
        return self::keysToCamelCase($params);
    }

    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    public static function kebabKeysToCamelCase(array $params): array
    {
        return self::keysToCamelCase($params, false);
    }

    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    public static function keysToCamelCase(array $params, $snakeCase = true): array
    {
        if (empty($params)) {
            return $params;
        }

        $keys = array_keys($params);
        $signature = implode('/', $keys) . $snakeCase;
        if (!isset(self::$cache[$signature]))
        {
            $keys = implode('|>~|.520f0fb,<|',$keys);
            if ($snakeCase)
            {
                $keys = StringHelper::snakeCaseToCamelCase($keys);
            } else
            {
                $keys = StringHelper::kebabCaseToCamelCase($keys);
            }
            $keys = explode('|>~|.520f0fb,<|', $keys);
            self::$cache[$signature] = $keys;
        }

        return array_combine(self::$cache[$signature], $params);
    }
}