<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Tools\Storage\Resource;

use DateTimeInterface;
use LetsCompose\Core\Tools\System\SystemGroupInfoInterface;
use LetsCompose\Core\Tools\System\SystemUserInfoInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class AbstractResourceInfo implements ResourceInfoInterface
{
    private ResourceTypeEnum $type;

    private SystemUserInfoInterface $owner;

    private SystemGroupInfoInterface $group;

    private DateTimeInterface $accessedAt;

    private DateTimeInterface $updatedAt;

    private DateTimeInterface $createdAt;

    /**
     * @return ResourceTypeEnum
     */
    public function getType(): ResourceTypeEnum
    {
        return $this->type;
    }

    /**
     * @param ResourceTypeEnum $type
     * @return AbstractResourceInfo
     */
    public function setType(ResourceTypeEnum $type): AbstractResourceInfo
    {
        $this->type = $type;
        return $this;
    }

    public function getOwner(): SystemUserInfoInterface
    {
        return $this->owner;
    }

    public function setOwner(SystemUserInfoInterface $owner): self
    {
        $this->owner = $owner;
        return $this;
    }

    public function getGroup(): SystemGroupInfoInterface
    {
        return $this->group;
    }

    public function setGroup(SystemGroupInfoInterface $group): self
    {
        $this->group = $group;
        return $this;
    }

    public function getAccessedAt(): DateTimeInterface
    {
        return $this->accessedAt;
    }

    public function setAccessedAt(DateTimeInterface $accessedAt): self
    {
        $this->accessedAt = $accessedAt;
        return $this;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
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


}