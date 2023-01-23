<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\FileSystem\Local\Resource\Action\File;

use LetsCompose\Core\Storage\Actions\AbstractAction;
use LetsCompose\Core\Storage\FileSystem\Resource\FileInterface;

class IsWritableAction extends AbstractAction
{
    protected const STORAGE_METHOD  = 'isWritable';

    protected function isWritable(FileInterface $resource): bool
    {
        $fullFilePath = $this->getStorage()->getFullPath($resource);
        return file_exists($fullFilePath) && is_writable($fullFilePath);
    }
}