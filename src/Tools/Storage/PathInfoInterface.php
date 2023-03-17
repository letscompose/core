<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Tools\Storage;

interface PathInfoInterface
{
    public function isExists(): bool;
    public function isFile(): bool;
    public function isSymLink(): bool;
    public function isDir(): bool;
    public function isReadable(): bool;
    public function isWritable(): bool;
    public function isAbsolutePath();
    public function getBasePath(): ?string;
    public function getPath(): ?string;
    public function getFileName(): ?string;
    public function getExtension(): ?string;
    public function hasExtension(): bool;
}