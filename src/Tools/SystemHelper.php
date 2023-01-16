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
        return (new SystemUserInfo())
            ->setUserId($systemUserId)
            ->setName($userInfo['name'])
            ->setHomePath($userInfo['dir'])
            ->setGroup(self::getGroupInfoByUid($userInfo['gid']))
        ;
    }

    public static function getGroupInfoByUid(int $systemGroupId): SystemGroupInfoInterface
    {
        $groupInfo = posix_getgrgid($systemGroupId);
        return (new SystemGroupInfo())
            ->setGroupId($systemGroupId)
            ->setName($groupInfo['name'])
        ;
    }

}