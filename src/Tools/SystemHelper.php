<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Tools;

use LetsCompose\Core\Tools\System\SystemGroupInfo;
use LetsCompose\Core\Tools\System\SystemGroupInfoInterface;
use LetsCompose\Core\Tools\System\SystemUserInfo;
use LetsCompose\Core\Tools\System\SystemUserInfoInterface;

class SystemHelper
{
    public static function getUserInfoByUid(int $systemUserId): SystemUserInfoInterface
    {
        $userInfo = posix_getpwuid($systemUserId);

        $userName = SystemUserInfo::UNRESOLVED_USER_NAME;
        $homePath = null;
        $resolved = false;

        if (is_array($userInfo))
        {
            $userName = $userInfo['name'];
            $homePath = $userInfo['dir'];
            $resolved = true;
        }

        return (new SystemUserInfo())
            ->setUserId($systemUserId)
            ->setName($userName)
            ->setHomePath($homePath)
            ->setGroup(self::getGroupInfoByUid($userInfo['gid']))
            ->setResolved($resolved)
        ;
    }

    public static function getGroupInfoByUid(int $systemGroupId): SystemGroupInfoInterface
    {
        $groupInfo = posix_getgrgid($systemGroupId);
        $groupName = SystemGroupInfo::UNRESOLVED_GROUP_NAME;
        $resolved = false;

        if (is_array($groupInfo))
        {
            $groupName = $groupInfo['name'];
            $resolved = true;
        }

        return (new SystemGroupInfo())
            ->setGroupId($systemGroupId)
            ->setName($groupName)
            ->setResolved($resolved)
        ;
    }

}