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

class StringPlaceholderHelper
{
    public const  PLACEHOLDER_TOKEN_BRACKETS = '{.}';

    public const PLACEHOLDER_TOKEN_DOUBLE_BRACKETS = '{{.}}';

    public const PLACEHOLDER_TOKEN_PERCENT = '%.%';

    private const PLACEHOLDER_TOKEN_MAP = [
        self::PLACEHOLDER_TOKEN_BRACKETS,
        self::PLACEHOLDER_TOKEN_DOUBLE_BRACKETS,
        self::PLACEHOLDER_TOKEN_PERCENT,
    ];

    private const PLACEHOLDER_TOKEN_REGEX_MAP = [
        self::PLACEHOLDER_TOKEN_BRACKETS => '/(?<={)[a-z_\-\d]+(?=})/mui',
        self::PLACEHOLDER_TOKEN_DOUBLE_BRACKETS => '/(?<={{)[a-z_\-\d]+(?=}})/mui',
        self::PLACEHOLDER_TOKEN_PERCENT => '/(?<=%)[a-z_\-\d]+(?=%)/mui',
    ];


    private static array $placeholderTokenParts = [];

    /**
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     */
    public static function fillPlaceholders(string $string, array $placeholders, string $token = self::PLACEHOLDER_TOKEN_BRACKETS): string
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
        if (!$placeholders) {
            return $string;
        }

        /**
         * ensure what string have placeholders to fill
         */
        $stringPlaceholders = self::getStringPlaceholders($string, $token);
        if (!$stringPlaceholders) {
            return $string;
        }

        /**
         * check if we have any match between string placeholders and placeholder replacements
         */
        $stringPlaceholders = array_flip($stringPlaceholders);
        $placeholders = array_intersect_key($placeholders, $stringPlaceholders);
        if (!$placeholders) {
            return $string;
        }

        if (false === isset(self::$placeholderTokenParts[$token]))
        {
            self::$placeholderTokenParts[$token] = explode('.', $token);
        }

        [$tokenLeftPart, $tokenRightPart] = self::$placeholderTokenParts[$token];
        /**
         * prepare placeholder replacements and check her types
         */
        $replacements = [];
        foreach ($placeholders as $key => $value) {
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

    public static function getStringPlaceholders(string $string, string $token = self::PLACEHOLDER_TOKEN_BRACKETS ): array {
        $pattern = self::PLACEHOLDER_TOKEN_REGEX_MAP[$token];
        preg_match_all($pattern, $string, $stringPlaceholders);
        return $stringPlaceholders ? $stringPlaceholders[0] : [];
    }
}