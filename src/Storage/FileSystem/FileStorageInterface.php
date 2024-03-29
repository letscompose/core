<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\FileSystem;

use LetsCompose\Core\Storage\FileSystem\Resource\DirectoryInterface;
use LetsCompose\Core\Storage\FileSystem\Resource\FileInterface;
use LetsCompose\Core\Storage\ResourceStorageInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
interface FileStorageInterface extends ResourceStorageInterface
{
    public function initFile(string $path): FileInterface;

    public function initDirectory(string $path): DirectoryInterface;

    public function readLine(FileInterface $file): mixed;

    public function flush(FileInterface $file): bool;

    public function createDirectory(DirectoryInterface $directory): DirectoryInterface;
}