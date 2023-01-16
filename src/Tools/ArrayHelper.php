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
    public static function keysToLowerCamelCase(array $params): array
    {
        if (empty($params)) {
            ExceptionHelper::create(new InvalidArgumentException())
                ->message('can\'t apply function to empty array')
                ->throw();
        }

        $signature = sha1(json_encode($params));
        if (isset(self::$cache[$signature]))
        {
            return self::$cache[$signature];
        }

        $keys = implode('|>~|.520f0fb,<|',array_keys($params));
        $keys = StringHelper::toLowerCamelCase($keys);
        $keys = explode('|>~|.520f0fb,<|', $keys);
        $result = array_combine($keys, $params);

        return self::$cache[$signature] = $result;
    }
}