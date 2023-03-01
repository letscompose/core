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
use LetsCompose\Core\Storage\Exception\FileNotFoundException;
use LetsCompose\Core\Storage\Exception\FileNotReadableException;
use LetsCompose\Core\Storage\Exception\FileNotWritableException;
use LetsCompose\Core\Storage\FileSystem\Enum\FileOpenModeEnum;
use LetsCompose\Core\Storage\FileSystem\Resource\FileInterface;
use LetsCompose\Core\Tools\ExceptionHelper;

class OpenAction extends AbstractAction
{
    protected const STORAGE_METHOD  = 'open';

    protected function open(FileInterface $file, FileOpenModeEnum $mode = FileOpenModeEnum::READ): FileInterface
    {
        $storage = $this->getStorage();

        if (false === $storage->isExists($file))
        {
            ExceptionHelper::create(new FileNotFoundException())
                ->setMessage('Can\'t open file at path [%s]. File does not exist on storage [%s]', $storage->getFullPath($file), $file->getStorageClass())
                ->throw()
            ;
        }

        switch ($mode) {
            case FileOpenModeEnum::READ:
                if (false === $storage->isReadable($file)) {
                    ExceptionHelper::create(new FileNotReadableException())
                        ->setMessage('Not readable file at path [%s] on storage [%s]', $storage->getFullPath($file), $file->getStorageClass())
                        ->throw();
                }
                break;
            case FileOpenModeEnum::WRITE:
            case FileOpenModeEnum::APPEND:
                if (false === $storage->isWritable($file)) {
                    ExceptionHelper::create(new FileNotWritableException())
                        ->setMessage('Not writable file at path [%s] on storage [%s]', $storage->getFullPath($file), $file->getStorageClass())
                        ->throw();
                }
                break;
            default:
                ExceptionHelper::create(new FileNotWritableException())
                    ->setMessage('Not implemented open file mode [%s]. File open mode can be only one of theses [%s]', $mode->name)
                    ->throw();
        }

        $fullFilePath = $storage->getFullPath($file);

        $stream = fopen($fullFilePath, $mode->mode() );

        return $file->setStream($stream);
    }
}