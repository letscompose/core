<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\FileSystem\Resource;

use LetsCompose\Core\Storage\Resource\ResourceInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
interface FileInterface extends ResourceInterface
{
    public function getExtension(): ?string;

    public function getDirectoryPath(): string;

    public function setMimeType(string $mimeType): FileInterface;

    public function getMimeType(): ?string;

    public function setSize(int $size): FileInterface;

    public function getSize(): int;
}