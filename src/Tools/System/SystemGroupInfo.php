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
    private string $name;
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

}