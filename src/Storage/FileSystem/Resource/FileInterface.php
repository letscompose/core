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
    /**
     * @return string|null
     */
    public function getExtension(): ?string;

    /**
     * @param string $mimeType
     * @return FileInterface
     */
    public function setMimeType(string $mimeType): FileInterface;

    /**
     * @return string|null
     */
    public function getMimeType(): ?string;

    /**
     * @param int $size
     * @return FileInterface
     */
    public function setSize(int $size): FileInterface;

    /**
     * @return int
     */
    public function getSize(): int;
}