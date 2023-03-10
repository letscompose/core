<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\FileLoader;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\InvalidArgumentException;
use LetsCompose\Core\Tools\ObjectHelper;
use LetsCompose\Core\Tools\Storage\PathHelper;

abstract class AbstractFileLoader implements FileLoaderInterface
{
    /**
     * @var FileLoaderInterface[]
     */
    private static array $instance = [];

    protected string $path;

    /**
     * @throws ExceptionInterface
     */
    public static function getInstance(string $path): FileLoaderInterface
    {
        $path = PathHelper::normalize($path);
        if (isset(self::$instance[$path]))
        {
            return self::$instance[$path];
        }

        if (false === PathHelper::isAbsolute($path)) {
            throw (new InvalidArgumentException())
                ->setMessage(
                    'You try to use relative path [%s] for configure [%s], instead you must provide an absolute path beginning by "/"',
                    $path,
                    ObjectHelper::getClassShortName(get_called_class())
                );
        }

        return self::$instance[$path] = self::createInstance($path);
    }

    protected static function createInstance(string $path): FileLoaderInterface
    {
        $calledClass = \get_called_class();
        return (new $calledClass)
            ->setPath(
                $path
            )
        ;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    protected function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    abstract public function getContents(string $path): string;

    abstract public function supports(string $path): bool;
}