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

use LetsCompose\Core\Storage\Resource\ResourceInterface;
use UnitEnum;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
interface ResourceStorageInterface
{
    public const OPERATION_CREATE = 'create';

    public const OPERATION_READ = 'read';

    public const OPERATION_UPDATE = 'update';

    public const OPERATION_DELETE = 'delete';

    public function create(ResourceInterface $resource): ResourceInterface;

    public function open(ResourceInterface $resource, UnitEnum $mode): ResourceInterface;

    public function read(ResourceInterface $resource): mixed;

    public function write(ResourceInterface $resource, mixed $data): mixed;

    public function close(ResourceInterface $resource): ResourceInterface;

    public function remove(ResourceInterface $resource): ResourceInterface;

    public function isExists(ResourceInterface $resource): bool;

    public function isReadable(ResourceInterface $resource): bool;

    public function isWritable(ResourceInterface $resource): bool;

    public function setRootPath(string $path): self;

    public function getRootPath(): string;

    public function getFullPath(ResourceInterface $resource): string;

    public function isResourceSupported(string $resourceClass): bool;

    public function initResource(string $resourceClass): ResourceInterface;
}