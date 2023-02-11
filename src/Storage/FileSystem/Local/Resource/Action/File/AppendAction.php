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
use LetsCompose\Core\Storage\FileSystem\Enum\FileOpenModeEnum;
use LetsCompose\Core\Storage\FileSystem\Resource\FileInterface;

class AppendAction extends AbstractAction
{
    protected const STORAGE_METHOD  = 'append';

    protected function append(FileInterface $file, string $data, int $length = null): FileInterface
    {
        $storage = $this->getStorage();

        if (false === $file->isOpen())
        {
            $storage->open($file, FileOpenModeEnum::APPEND);
        }

        fwrite($file->getStream(), $data, $length);

        return $file;
    }
}