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
class AbstractFileSystemResourceInfo implements FileSystemResourceInfoInterface
{
    protected UserRefInterface $owner;

    protected DateTimeInterface $createdAt;

    protected ?DateTimeInterface $accessedAt = null;

    protected ?DateTimeInterface $updatedAt = null;

    public function getOwner(): UserRefInterface
    {
        return $this->owner;
    }

    public function setOwner(UserRefInterface $owner): self
    {
        $this->owner = $owner;
        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getAccessedAt(): ?DateTimeInterface
    {
        return $this->accessedAt;
    }

    public function setAccessedAt(?DateTimeInterface $accessedAt): self
    {
        $this->accessedAt = $accessedAt;
        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}