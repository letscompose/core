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
interface StorageInterface
{
    /**
     * @var string
     */
    public const OPEN_MODE_READ = 'read';

    /**
     * @var string
     */
    public const OPEN_MODE_WRITE = 'write';

    /**
     * @var string
     */
    public const OPEN_MODE_RE_WRITE = 're-write';

    /**
     * @var string
     */
    public const OPEN_MODE_APPEND = 'append';

    /**
     * @param ResourceInterface $resource
     * @param string|null $mode
     * @return ResourceInterface
     */
    public function open(ResourceInterface $resource, ?string $mode = null): ResourceInterface;

    /**
     * @param ResourceInterface $resource
     * @return mixed
     */
    public function read(ResourceInterface $resource): mixed;

    /**
     * @param ResourceInterface $resource
     * @param mixed $data
     * @return mixed
     */
    public function write(ResourceInterface $resource, mixed $data): mixed;

    /**
     * @param ResourceInterface $resource
     * @return ResourceInterface
     */
    public function close(ResourceInterface $resource): ResourceInterface;

    /**
     * @param ResourceInterface $resource
     * @return ResourceInterface
     */
    public function remove(ResourceInterface $resource): ResourceInterface;

    /**
     * @param ResourceInterface $resource
     * @return bool
     */
    public function isExists(ResourceInterface $resource): bool;

    /**
     * @param ResourceInterface $resource
     * @return bool
     */
    public function isReadable(ResourceInterface $resource): bool;

    /**
     * @param ResourceInterface $resource
     * @return bool
     */
    public function isWritable(ResourceInterface $resource): bool;

    /**
     * @param string $path
     * @return StorageInterface
     */
    public function setRootPath(string $path): StorageInterface;

    /**
     * @return string
     */
    public function getRootPath(): string;

    /**
     * @param ResourceInterface $resource
     * @return string
     */
    public function getFullPath(ResourceInterface $resource): string;

    /**
     * @param ConfigInterface $config
     * @return StorageInterface
     */
    public function setConfig(ConfigInterface $config): StorageInterface;

    /**
     * @return ConfigInterface
     */
    public function getConfig(): ConfigInterface;

    /**
     * @param ResourceInterface $resource
     * @return bool
     */
    public function isResourceSupported(ResourceInterface $resource): bool;
}