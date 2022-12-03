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
use LetsCompose\Core\Storage\Exception\FileNotFoundException;
use LetsCompose\Core\Storage\Exception\FileNotReadableException;
use LetsCompose\Core\Storage\FileSystem\Enum\FileOpenModeEnum;
use LetsCompose\Core\Storage\FileSystem\Resource\FileInterface;
use LetsCompose\Core\Storage\Resource\ResourceInterface;
use LetsCompose\Core\Tools\ExceptionHelper;

class OpenAction extends AbstractAction
{
    protected const STORAGE_METHOD  = 'open';

    protected function open(ResourceInterface $file, ?FileOpenModeEnum $mode = FileOpenModeEnum::READ): FileInterface
    {
        $storage = $this->getStorage();

        if (false === $storage->isExists($file))
        {
            ExceptionHelper::create(new FileNotFoundException())
                ->message('Can\'t open file at path [%s]. File does not exist on storage [%s]', $file->getPath(), $file->getStorageClass())
                ->throw()
            ;
        }


        switch ($mode) {
            case FileOpenModeEnum::READ:
                if (false === $storage->isReadable($file)) {
                    ExceptionHelper::create(new FileNotReadableException())
                        ->message('Not readable file at path [%s] on storage [%s]', $file->getPath(), $file->getStorageClass())
                        ->throw();
                }
                break;
        }

        $fullFilePath = $storage->getFullPath($file);

        $stream = fopen($fullFilePath, $mode->mode() );

        return $file->setStream($stream);
    }
}