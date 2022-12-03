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

class CloseAction extends AbstractAction
{
    protected const STORAGE_METHOD  = 'close';

    protected function close(FileInterface|ResourceInterface $file): string|bool
    {
        $result = false;
        if ($file->isOpen())
        {
            $stream = $file->getStream();
            fflush($stream);
            $result = fclose($stream);
        }
        return $result;
    }
}