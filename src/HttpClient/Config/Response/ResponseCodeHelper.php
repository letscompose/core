<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace LetsCompose\Core\HttpClient\Config\Response;

use LetsCompose\Core\HttpClient\Config\ConfigInterface;

class ResponseCodeHelper
{
    public static function isHttpResponseCode(int $code): bool
    {
        return self::codeInRange($code, 100, 599);
    }

    public static function isInformation(int $code): bool
    {
        return self::codeInRange($code, 100, 199);
    }

    public static function isSuccessful(int $code): bool
    {
        return self::codeInRange($code, 200, 299);
    }

    public static function isRedirect(int $code): bool
    {
        return self::codeInRange($code, 300, 399);
    }

    public static function isClientError(int $code): bool
    {
        return self::codeInRange($code, 400, 499);
    }

    public static function isServerError(int $code): bool
    {
        return self::codeInRange($code, 500, 500);
    }

    private static function codeInRange(int $code, int $start, int $end): bool
    {
        return ($code >= $start) && ($code <= $end);
    }
}