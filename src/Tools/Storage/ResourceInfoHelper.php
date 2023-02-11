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
use LetsCompose\Core\Exception\InvalidArgumentException;
use LetsCompose\Core\Exception\NotReadableResourceException;
use LetsCompose\Core\Tools\ExceptionHelper;
use LetsCompose\Core\Tools\Storage\Resource\ResourceInfoInterface;
use LetsCompose\Core\Tools\Storage\Resource\DirectoryInfo;
use LetsCompose\Core\Tools\Storage\Resource\FileInfo;
use LetsCompose\Core\Tools\Storage\Resource\ResourceTypeEnum;
use LetsCompose\Core\Tools\SystemHelper;
use \DateTime;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class ResourceInfoHelper
{
    /**
     * @throws ExceptionInterface
     */
    public static function getResourceInfo(string $filePath): ResourceInfoInterface
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
        $resourceInfo = null;

        /**
         * refactored switch from
         * https://www.php.net/manual/fr/function.fileperms.php
         */
        switch ($stats['mode'] & 0xF000)
        {
            case 0x4000: // directory
                $resourceInfo = (new DirectoryInfo())
                    ->setType(ResourceTypeEnum::DIRECTORY);
                break;
            case 0x8000: // regular file
            case 0xA000: // symbolic link
                $resourceInfo = (new FileInfo())
                    ->setType(ResourceTypeEnum::FILE)
                    ->setSize($size)
                ;
                break;
            case 0xC000: // socket
            case 0x6000: // block special
            case 0x2000: // character special
            case 0x1000: // FIFO pipe
            default: // unknown
                ExceptionHelper::create(new InvalidArgumentException())
                    ->message('Can\'t get info for unsupported resource type');
        }

        return $resourceInfo
            ->setOwner($owner)
            ->setGroup($group)
            ->setAccessedAt($accessedAt)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($updatedAt)
        ;

    }
}