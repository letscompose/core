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

class PathInfo implements PathInfoInterface
{
    private bool $exists = false;
    private bool $file = false;
    private bool $dir = false;
    private bool $symLink = false;
    private bool $readable = false;
    private bool $writable = false;
    private bool $absolutePath = false;
    private string $path;
    private ?string $fileName;
    private ?string $extension;
    public function isExists(): bool
    {
        return $this->exists;
    }

    public function setExists(bool $exists): self
    {
        $this->exists = $exists;
        return $this;
    }

    public function isFile(): bool
    {
        return $this->file;
    }

    public function isSymLink(): bool
    {
        return $this->symLink;
    }

    public function setSymLink(bool $symLink): self
    {
        $this->symLink = $symLink;
        return $this;
    }

    public function setFile(bool $file): self
    {
        $this->file = $file;
        return $this;
    }

    public function isDir(): bool
    {
        return $this->dir;
    }

    public function setDir(bool $dir): self
    {
        $this->dir = $dir;
        return $this;
    }

    public function isReadable(): bool
    {
        return $this->readable;
    }

    public function setReadable(bool $readable): self
    {
        $this->readable = $readable;
        return $this;
    }

    public function isWritable(): bool
    {
        return $this->writable;
    }

    /**
     * @param bool $writable
     * @return PathInfo
     */
    public function setWritable(bool $writable): PathInfo
    {
        $this->writable = $writable;
        return $this;
    }

    public function isAbsolutePath(): bool
    {
        return $this->absolutePath;
    }

    public function setAbsolutePath(bool $absolutePath): self
    {
        $this->absolutePath = $absolutePath;
        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): self
    {
        $this->fileName = $fileName;
        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(?string $extension): self
    {
        $this->extension = $extension;
        return $this;
    }

    public function hasExtension(): bool
    {
        return !empty($this->extension);
    }

    public function getBasePath(): ?string
    {
        return basename($this->path);
    }
}