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

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\NotReadableResourceException;
use LetsCompose\Core\Tools\ExceptionHelper;
use LetsCompose\Core\Tools\SystemHelper;
use \DateTime;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class FileInfoHelper
{
    /**
     * @throws ExceptionInterface
     */
    public static function getFileInfo(string $filePath): FileInfoInterface
    {
        if (false === is_readable($filePath))
        {
            ExceptionHelper::create(new NotReadableResourceException())
                ->message('You try to get info on not readable resource [%s]', $filePath)
                ->throw()
                ;
        }

        $stats = stat($filePath);
        $owner = SystemHelper::getUserInfoByUid($stats['uid']);
        $group = SystemHelper::getGroupInfoByUid($stats['gid']);
        $size = $stats['size'];
        $accessedAt = (new DateTime())->setTimestamp($stats['atime']);
        $createdAt = (new DateTime())->setTimestamp($stats['ctime']);
        $updatedAt = (new DateTime())->setTimestamp($stats['mtime']);

        return (new FileInfo())
            ->setOwner($owner)
            ->setGroup($group)
            ->setSize($size)
            ->setAccessedAt($accessedAt)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($updatedAt)
        ;

    }
}