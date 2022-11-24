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

    public const OPEN_MODE_READ = 'read';

    public const OPEN_MODE_WRITE = 'write';

    public const OPEN_MODE_RE_WRITE = 're-write';

    public const OPEN_MODE_APPEND = 'append';

    public function open(ResourceInterface $resource, ?string $mode = null): ResourceInterface;

    public function read(ResourceInterface $resource): mixed;

    public function write(ResourceInterface $resource, mixed $data): mixed;

    public function close(ResourceInterface $resource): ResourceInterface;

    public function remove(ResourceInterface $resource): ResourceInterface;

    public function isExists(ResourceInterface $resource): bool;

    public function isReadable(ResourceInterface $resource): bool;

    public function isWritable(ResourceInterface $resource): bool;

    public function setRootPath(string $path): StorageInterface;

    public function getRootPath(): string;

    public function getFullPath(ResourceInterface $resource): string;

    public function setConfig(ConfigInterface $config): StorageInterface;

    public function getConfig(): ConfigInterface;

    public function isResourceSupported(ResourceInterface $resource): bool;
}