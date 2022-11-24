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
use LetsCompose\Core\Storage\Resource\ResourceInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
abstract class AbstractStorage implements StorageInterface
{
    protected string $rootPath;

    /**
     * @var string[]
     */
    protected array $supportedResources = [];

    protected ConfigInterface $config;

    public function getRootPath(): string
    {
        return $this->rootPath;
    }

    public function setRootPath(string $path): StorageInterface
    {
        $this->rootPath = $path;
        return $this;
    }

    public function setConfig(ConfigInterface $config): StorageInterface
    {
        $this->config = $config;
        return $this;
    }

    public function getConfig(): ConfigInterface
    {
        return $this->config;
    }

    public function isResourceSupported(ResourceInterface $resource): bool
    {
        return true === \in_array($resource::class, $this->supportedResources, true);
    }

}