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
    public const UNRESOLVED_USER_NAME = 'unresolved';

    private string $name = self::UNRESOLVED_USER_NAME;
    private int $userId;

    private SystemGroupInfoInterface $group;

    private ?string $homePath = null;

    private bool $resolved = false;

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
     * @return string|null
     */
    public function getHomePath(): ?string
    {
        return $this->homePath;
    }

    /**
     * @param string|null $homePath
     * @return SystemUserInfo
     */
    public function setHomePath(?string $homePath): SystemUserInfo
    {
        $this->homePath = $homePath;
        return $this;
    }

    /**
     * @return bool
     */
    public function isResolved(): bool
    {
        return $this->resolved;
    }

    /**
     * @param bool $resolved
     * @return SystemUserInfo
     */
    public function setResolved(bool $resolved): SystemUserInfo
    {
        $this->resolved = $resolved;
        return $this;
    }
}