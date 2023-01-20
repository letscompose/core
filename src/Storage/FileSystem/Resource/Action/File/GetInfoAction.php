<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\FileSystem\Resource\Action\File;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Object\User;
use LetsCompose\Core\Storage\Actions\AbstractAction;
use LetsCompose\Core\Storage\FileSystem\Resource\FileInfo;
use LetsCompose\Core\Storage\FileSystem\Resource\FileInfoInterface;
use LetsCompose\Core\Storage\FileSystem\Resource\FileInterface;
use LetsCompose\Core\Tools\Storage\ResourceInfoHelper;

class GetInfoAction extends AbstractAction
{
    protected const STORAGE_METHOD  = 'getInfo';

    /**
     * @throws ExceptionInterface
     */
    protected function getInfo(FileInterface $file): FileInfoInterface
    {
        $filePath = $this->getStorage()->getFullPath($file);
        $resourceInfo = ResourceInfoHelper::getResourceInfo($filePath);

        $ownerId = $resourceInfo->getOwner()->getUserId();

        $owner = new User();
        $owner->setRef($ownerId);
        $createdAt  = $resourceInfo->getCreatedAt();
        $updatedAt  = $resourceInfo->getUpdatedAt();
        $accessedAt = $resourceInfo->getAccessedAt() ;


        $fileInfo = new FileInfo();
        $fileInfo->setOwner($owner);
        $fileInfo->setCreatedAt($createdAt);
        $fileInfo->setUpdatedAt($updatedAt);
        $fileInfo->setAccessedAt($accessedAt);

        $file->setFileInfo($fileInfo);

        return $fileInfo;
    }
}