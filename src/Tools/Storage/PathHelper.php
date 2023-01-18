<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Tools\Storage;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class PathHelper
{
    /**
     * @param string $path
     * @return string
     */
    static function normalize(string $path): string
    {
        $parts = explode('/', trim($path, '/'));
        $result = [];
        foreach ($parts as $part) {
            $part = trim($part);
            if ($part == '.' || 0 === strlen($part)) {
                continue;
            }
            if ($part == '..') {
                array_pop($ret);
            } else {
                $result[] = $part;
            }
        }
        $finalPath = implode('/', $result);

        return (self::isAbsolute($path) ? '/' : null) . $finalPath;
    }

    /**
     * @param string $path
     * @return bool
     */
    static function isAbsolute(string $path): bool
    {
        return '/' === $path[0];
    }
}