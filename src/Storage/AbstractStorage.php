<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage;

use LetsCompose\Core\Storage\Config\ConfigInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
abstract class AbstractStorage implements StorageInterface
{
    /**
     * @var string
     */
    protected string $rootPath;

    /**
     * @var ConfigInterface
     */
    protected ConfigInterface $config;

    /**
     * @inheritDoc
     */
    public function getRootPath(): string
    {
        return $this->rootPath;
    }

    /**
     * @inheritDoc
     */
    public function setRootPath(string $path): StorageInterface
    {
        $this->rootPath = $path;
        return $this;
    }

    /**
     * @param ConfigInterface $config
     * @return AbstractStorage
     */
    public function setConfig(ConfigInterface $config): StorageInterface
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @return ConfigInterface
     */
    public function getConfig(): ConfigInterface
    {
        return $this->config;
    }
//
//    /**
//     * @inheritDoc
//     */
//    abstract public function open(string $path, ?string $mode = null): ResourceInterface;
//
//    /**
//     * @inheritDoc
//     */
//    abstract public function read(ResourceInterface $resource): mixed;
//
//    /**
//     * @inheritDoc
//     */
//    abstract public function write(ResourceInterface $resource, mixed $data): mixed;
//
//    /**
//     * @inheritDoc
//     */
//    abstract public function close(ResourceInterface $resource): ResourceInterface;


}