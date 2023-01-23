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

use LetsCompose\Core\Storage\Actions\AbstractAction;
use LetsCompose\Core\Storage\FileSystem\Resource\DirectoryInterface;

class IsReadableAction extends AbstractAction
{
    protected const STORAGE_METHOD  = 'isReadable';

    protected function isReadable(DirectoryInterface $directory): bool
    {
        $fullPath = $this->getStorage()->getFullPath($directory);
        return is_dir($fullPath) && is_readable($fullPath);
    }
}