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

class FlushAction extends AbstractAction
{
    protected const STORAGE_METHOD  = 'flush';

    protected function flush(FileInterface $file): string|bool
    {
        $result = false;
        if ($file->isOpen())
        {
            $stream = $file->getStream();
            $result = fsync($stream);
        }
        return $result;
    }
}