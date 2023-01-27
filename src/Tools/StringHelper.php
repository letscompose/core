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

class StringHelper
{
    public static array $cache = [];

    const STRING_SNAKE_TOKEN =  '_';
    const STRING_KEBAB_TOKEN =  '-';

    public static function snakeCaseToCamelCase(string $string): string
    {
        if (isset(self::$cache[$string]))
        {
            return self::$cache[$string];
        }

        $string = self::stringToPascalCase($string, self::STRING_SNAKE_TOKEN);

        return self::$cache[$string] = lcfirst($string);
    }

    public static function snakeCaseToPascalCase(string $string): string
    {
        return self::stringToPascalCase($string, self::STRING_SNAKE_TOKEN);
    }

    public static function kebabCaseToCamelCase(string $string): string
    {
        if (isset(self::$cache[$string]))
        {
            return self::$cache[$string];
        }

        $string = self::stringToPascalCase($string, self::STRING_KEBAB_TOKEN);

        return self::$cache[$string] = lcfirst($string);
    }

    public static function kebabCaseToPascalCase(string $string): string
    {
        return self::stringToPascalCase($string, self::STRING_KEBAB_TOKEN);
    }

    public static function stringToPascalCase(string $string, string $token): string
    {
        $signature = $string.$token;
        if (isset(self::$cache[$signature]))
        {
            return self::$cache[$signature];
        }

        $string = str_replace($token, '', ucwords($string, $token));

        return self::$cache[$signature] = $string;
    }

    /**
     * convert camelCase to snake_case
     * taken from stackoverflow
     * @url https://stackoverflow.com/questions/1993721/how-to-convert-pascalcase-to-pascal-case
     */
    public static function toSnakeCase(string $string): string {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $string, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }

    public static function stringToBytes(string $value): int
    {
        $unit = strtolower(mb_substr($value, -1 ));
        $bytes = intval(mb_substr($value, 0, -1), 10);

        switch ($unit)
        {
            case 'k':
            case 'kb':
                $bytes *= 1024;
                break 1;

            case 'm':
            case 'mb':
                $bytes *= 1048576;
                break 1;

            case 'g':
            case 'gb':
                $bytes *= 1073741824;
                break 1;

            default:
                break 1;
        }

        return $bytes;
    }
}