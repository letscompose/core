<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\Config;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\InvalidArgumentException;
use LetsCompose\Core\Tools\ExceptionHelper;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
abstract class AbstractConfig implements ConfigInterface
{
    /**
     * @var string
     */
    protected string $rootPath;

    /**
     * @var string
     */
    protected string $storageClass;

    /**
     * @param string $path
     * @return AbstractConfig
     */
    public function setRootPath(string $path): AbstractConfig
    {
        $this->rootPath = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getRootPath(): string
    {
        return $this->rootPath;
    }

    /**
     * @param string $class
     * @return AbstractConfig
     */
    public function setStorageClass(string $class): AbstractConfig
    {
        $this->storageClass = $class;
        return $this;
    }

    /**
     * @return string
     */
    public function getStorageClass(): string
    {
        return $this->storageClass;
    }



}