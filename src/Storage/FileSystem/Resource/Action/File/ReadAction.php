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
use LetsCompose\Core\Storage\Resource\ResourceInterface;

class ReadAction extends AbstractAction
{
    protected const STORAGE_METHOD  = 'read';

    protected function read(FileInterface|ResourceInterface $file, int $chunkSize = 1024): string|bool
    {
        $storage = $this->getStorage();
        if (!$file->isOpen())
        {
            $file = $storage->open($file);
        }
        $stream = $file->getStream();
        return !feof($stream) ? fread($stream, $chunkSize) : false;
    }
}