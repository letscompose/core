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

class SystemGroupInfo implements SystemGroupInfoInterface
{
    public const UNRESOLVED_GROUP_NAME = 'unresolved';

    private bool $resolved = false;

    private string $name = self::UNRESOLVED_GROUP_NAME;
    private int $groupId;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): SystemGroupInfo
    {
        $this->name = $name;
        return $this;
    }

    public function getGroupId(): int
    {
        return $this->groupId;
    }

    public function setGroupId(int $groupId): SystemGroupInfo
    {
        $this->groupId = $groupId;
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
     * @return SystemGroupInfo
     */
    public function setResolved(bool $resolved): SystemGroupInfo
    {
        $this->resolved = $resolved;
        return $this;
    }
}