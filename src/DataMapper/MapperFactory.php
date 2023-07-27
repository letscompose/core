<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\DataMapper;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\NotExistsException;
use LetsCompose\Core\Exception\NotReadableException;
use LetsCompose\Core\Parser\YamlContentParserFactory;
use LetsCompose\Core\Storage\Exception\FileNotFoundException;
use LetsCompose\Core\Tools\Storage\PathHelper;

class MapperFactory
{
    /**
     * @throws ExceptionInterface
     */
    public static function create(string $mappingFilePath, array $params): MapperInterface
    {
        $pathInfo = PathHelper::getInfo($mappingFilePath);

        if (false === $pathInfo->isExists())
        {
            throw (new NotExistsException())
                ->setMessage('Path [%s] doest not exist', $mappingFilePath)
            ;
        }

        if (false === $pathInfo->isFile())
        {
            throw (new FileNotFoundException())
                ->setMessage('can\'t process file at path [%s], file does not exist', $mappingFilePath)
            ;
        }

        if (false === $pathInfo->isReadable())
        {
            throw (new NotReadableException())
                ->setMessage('can\'t process file at path [%s], file does not readable', $mappingFilePath)
            ;
        }

        $yamlContentParser = YamlContentParserFactory::create($pathInfo->getPath(), $params);
        $content = $yamlContentParser->parse($pathInfo->getFileName());
        return Mapper::create($content);
    }
}