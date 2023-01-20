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

use DateTimeInterface;
use LetsCompose\Core\Interface\UserRefInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class FileInfo implements FileInfoInterface
{
    private UserRefInterface $owner;

    private DateTimeInterface $createdAt;

    private ?DateTimeInterface $accessedAt = null;

    private ?DateTimeInterface $updatedAt = null;

    private int $size = 0;

    public function getOwner(): UserRefInterface
    {
        return $this->owner;
    }

    public function setOwner(UserRefInterface $owner): FileInfo
    {
        $this->owner = $owner;
        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): FileInfo
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getAccessedAt(): ?DateTimeInterface
    {
        return $this->accessedAt;
    }

    public function setAccessedAt(?DateTimeInterface $accessedAt): FileInfo
    {
        $this->accessedAt = $accessedAt;
        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): FileInfo
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): FileInfo
    {
        $this->size = $size;
        return $this;
    }
}