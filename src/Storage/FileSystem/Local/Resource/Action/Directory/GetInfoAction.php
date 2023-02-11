<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\FileSystem\Local\Resource\Action\Directory;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Object\User;
use LetsCompose\Core\Storage\Actions\AbstractAction;
use LetsCompose\Core\Storage\FileSystem\Resource\DirectoryInfo;
use LetsCompose\Core\Storage\FileSystem\Resource\DirectoryInfoInterface;
use LetsCompose\Core\Storage\FileSystem\Resource\DirectoryInterface;
use LetsCompose\Core\Tools\Storage\ResourceInfoHelper;

class GetInfoAction extends AbstractAction
{
    protected const STORAGE_METHOD  = 'getInfo';

    /**
     * @throws ExceptionInterface
     */
    protected function getInfo(DirectoryInterface $directory): DirectoryInfoInterface
    {
        $directoryPath = $this->getStorage()->getFullPath($directory);
        $resourceInfo = ResourceInfoHelper::getResourceInfo($directoryPath);

        $ownerId = $resourceInfo->getOwner()->getUserId();

        $owner = new User();
        $owner->setRef($ownerId);
        $createdAt  = $resourceInfo->getCreatedAt();
        $updatedAt  = $resourceInfo->getUpdatedAt();
        $accessedAt = $resourceInfo->getAccessedAt() ;


        $resourceInfo = new DirectoryInfo();
        $resourceInfo->setOwner($owner);
        $resourceInfo->setCreatedAt($createdAt);
        $resourceInfo->setUpdatedAt($updatedAt);
        $resourceInfo->setAccessedAt($accessedAt);

        $directory->setInfo($resourceInfo);

        return $resourceInfo;
    }
}