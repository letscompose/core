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

class IsExistsAction extends AbstractAction
{
    protected const STORAGE_METHOD  = 'isExists';

    protected function isExists(FileInterface $resource): bool
    {
        $fullFilePath = $this->getStorage()->getFullPath($resource);
        return file_exists($fullFilePath) && is_file($fullFilePath);
    }
}