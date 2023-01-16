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

    public const  PLACEHOLDER_TOKEN_BRACKETS = '{.}';

    public const PLACEHOLDER_TOKEN_DOUBLE_BRACKETS = '{{.}}';

    public const PLACEHOLDER_TOKEN_PERCENT = '%.%';

    private const PLACEHOLDER_TOKEN_MAP = [
        self::PLACEHOLDER_TOKEN_BRACKETS,
        self::PLACEHOLDER_TOKEN_DOUBLE_BRACKETS,
        self::PLACEHOLDER_TOKEN_PERCENT,
    ];

    private const PLACEHOLDER_TOKEN_REGEX_MAP = [
        self::PLACEHOLDER_TOKEN_BRACKETS => '/(?<={)[a-z_\-]+(?=})/mui',
        self::PLACEHOLDER_TOKEN_DOUBLE_BRACKETS => '/(?<={{)[a-z_\-]+(?=}})/mui',
        self::PLACEHOLDER_TOKEN_PERCENT => '/(?<=%)[a-z_\-]+(?=%)/mui',
    ];

    private static array $placeHolderTokenParts = [];

    public static function toLowerCamelCase(string $string): string
    {
        return self::stringToCamelCase($string, true);
    }

    public static function toCamelCase(string $string): string
    {
        return self::stringToCamelCase($string);
    }

    public static function stringToCamelCase(string $string, bool $firstLower = false): string
    {
        $signature = $string.$firstLower;
        if (isset(self::$cache[$signature]))
        {
            return self::$cache[$signature];
        }

        $string = str_replace('_', '', ucwords($string, '_'));

        if ($firstLower) {
            $string = lcfirst($string);
        }

        return self::$cache[$signature] = $string;
    }

    /**
     * @throws ExceptionInterface
     */
    public static function fillPlaceHolders(string $string, array $placeHolders, string $token = self::PLACEHOLDER_TOKEN_BRACKETS): string
    {
        if (false === in_array($token, self::PLACEHOLDER_TOKEN_MAP))
        {
            ExceptionHelper::create(new InvalidArgumentException())
                ->message('Unknown placeholder token format, you can use only theses [%s]', implode(',',self::PLACEHOLDER_TOKEN_MAP))
                ->throw()
            ;
        }

        /**
         * if here no placeholder replacements, return initial string
         */
        if (!$placeHolders) {
            return $string;
        }

        /**
         * ensure what string have placeholders to fill
         */
        $stringPlaceHolders = self::getStringPlaceHolders($string, self::PLACEHOLDER_TOKEN_REGEX_MAP[$token]);
        if (!$stringPlaceHolders) {
            return $string;
        }

        /**
         * check if we have any match between string placeholders and placeholder replacements
         */
        $stringPlaceHolders = array_flip($stringPlaceHolders);
        $placeHolders = array_intersect_key($placeHolders, $stringPlaceHolders);
        if (!$placeHolders) {
            return $string;
        }

        if (false === isset(self::$placeHolderTokenParts[$token]))
        {
            self::$placeHolderTokenParts[$token] = explode('.', $token);
        }

        [$tokenLeftPart, $tokenRightPart] = self::$placeHolderTokenParts[$token];
        /**
         * prepare placeholder replacements and check her types
         */
        $replacements = [];
        foreach ($placeHolders as $key => $value) {
            if (is_string($value) || is_int($value) || is_float($value))  {
                $replacements[$tokenLeftPart. $key . $tokenRightPart] = $value;
            } else {
                throw new \InvalidArgumentException(sprintf('not supported place holder value, for key [{%s}] in string ["%s"]', $key, $string));
            }
        }

        /**
         * finally replace placeholders
         */
        return \strtr($string, $replacements);
    }

    private static function getStringPlaceHolders(string $string, string $pattern): array {
        preg_match_all($pattern, $string, $stringPlaceHolders);
        return $stringPlaceHolders ? $stringPlaceHolders[0] : [];
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
