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

use DateTimeInterface;
use LetsCompose\Core\Tools\System\SystemGroupInfoInterface;
use LetsCompose\Core\Tools\System\SystemUserInfoInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class FileInfo implements FileInfoInterface
{
    private SystemUserInfoInterface $owner;

    private SystemGroupInfoInterface $group;

    private int $size = 0;

    private DateTimeInterface $accessedAt;

    private DateTimeInterface $updatedAt;

    private DateTimeInterface $createdAt;

    /**
     * @return SystemUserInfoInterface
     */
    public function getOwner(): SystemUserInfoInterface
    {
        return $this->owner;
    }

    /**
     * @param SystemUserInfoInterface $owner
     * @return FileInfo
     */
    public function setOwner(SystemUserInfoInterface $owner): FileInfo
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * @return SystemGroupInfoInterface
     */
    public function getGroup(): SystemGroupInfoInterface
    {
        return $this->group;
    }

    /**
     * @param SystemGroupInfoInterface $group
     * @return FileInfo
     */
    public function setGroup(SystemGroupInfoInterface $group): FileInfo
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     * @return FileInfo
     */
    public function setSize(int $size): FileInfo
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getAccessedAt(): DateTimeInterface
    {
        return $this->accessedAt;
    }

    /**
     * @param DateTimeInterface $accessedAt
     * @return FileInfo
     */
    public function setAccessedAt(DateTimeInterface $accessedAt): FileInfo
    {
        $this->accessedAt = $accessedAt;
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeInterface $updatedAt
     * @return FileInfo
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): FileInfo
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     * @return FileInfo
     */
    public function setCreatedAt(DateTimeInterface $createdAt): FileInfo
    {
        $this->createdAt = $createdAt;
        return $this;
    }


}