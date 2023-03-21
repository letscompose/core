<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Parser;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\InvalidPathException;
use LetsCompose\Core\Exception\NotExistsException;
use LetsCompose\Core\Exception\NotReadableException;
use LetsCompose\Core\FileLoader\YamlFileLoader;
use LetsCompose\Core\Tools\Storage\PathHelper;

class YamlContentParserFactory
{
    /**
     * @throws ExceptionInterface
     */
    public static function create(string $path, array $contentParams = []): YamlContentParser
   {
       $path = PathHelper::getInfo($path);
       if (false === $path->isExists())
       {
           throw (new NotExistsException())
               ->setMessage('You try to access not existing path [%s]', $path);
       }

       if (false === $path->isDir())
       {
           throw (new InvalidPathException())
               ->setMessage('Path [%s] not a directory', $path);
       }

       if (false === $path->isReadable())
       {
           throw (new NotReadableException())
               ->setMessage('Not readable path [%s]', $path);
       }

       $fileLoader = YamlFileLoader::getInstance($path->getPath());
       $contentLoader = new YamlContentParser($fileLoader);
       return $contentLoader->setContentPlaceholderParameters($contentParams);
   }
}