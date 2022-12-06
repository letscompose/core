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

use LetsCompose\Core\Storage\Actions\AbstractAction;
use LetsCompose\Core\Storage\FileSystem\Resource\FileInterface;

class IsReadableAction extends AbstractAction
{
    protected const STORAGE_METHOD  = 'isReadable';

    protected function isReadable(FileInterface $resource): bool
    {
        $fullFilePath = $this->getStorage()->getFullPath($resource);
        return is_readable($fullFilePath);
    }
}