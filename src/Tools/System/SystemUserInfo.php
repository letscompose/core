<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Tools\System;

class SystemUserInfo implements SystemUserInfoInterface
{
    private string $name;
    private int $userId;

    private SystemGroupInfoInterface $group;

    private string $homePath;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): SystemUserInfo
    {
        $this->name = $name;
        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): SystemUserInfo
    {
        $this->userId = $userId;
        return $this;
    }

    public function getGroup(): SystemGroupInfoInterface
    {
        return $this->group;
    }

    public function setGroup(SystemGroupInfoInterface $group): SystemUserInfo
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @return string
     */
    public function getHomePath(): string
    {
        return $this->homePath;
    }

    public function setHomePath(string $homePath): SystemUserInfo
    {
        $this->homePath = $homePath;
        return $this;
    }

}