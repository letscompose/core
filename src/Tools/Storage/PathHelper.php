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

    static function getInfo(string $path): PathInfoInterface
    {
        $pathInfo = (new PathInfo())
            ->setExists(file_exists($path))
            ->setAbsolutePath(self::isAbsolute($path))
            ->setPath($path)
        ;

        $infos = pathinfo($pathInfo->getPath());
        $baseName = $infos['basename'];
        $pos = strpos($baseName, '.');

        if (false !== $pos && (0 !== $pos || 1 < substr_count($baseName, '.')))
        {
            $pathInfo->setExtension($infos['extension'] ?? null);
        }

        if (false === $pathInfo->isExists())
        {
            return $pathInfo;
        }

        $pathInfo
            ->setFile(is_file($path))
            ->setDir(is_dir($path))
            ->setSymLink(is_link($path))
            ->setReadable(is_readable($path))
            ->setWritable(is_writable($path))
        ;

        if ($pathInfo->isFile())
        {
            $pathInfo->setFileName($baseName);
            $pathInfo->setPath($infos['dirname']);
        }
        return $pathInfo;
    }
}