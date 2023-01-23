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

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Storage\Actions\AbstractAction;
use LetsCompose\Core\Storage\Exception\FileNotFoundException;
use LetsCompose\Core\Storage\Exception\UnableToRemoveFileException;
use LetsCompose\Core\Storage\FileSystem\Resource\FileInterface;
use LetsCompose\Core\Tools\ExceptionHelper;

class RemoveAction extends AbstractAction
{
    protected const STORAGE_METHOD  = 'remove';

    /**
     * @throws ExceptionInterface
     */
    protected function remove(FileInterface $file): FileInterface
    {
        $storage = $this->getStorage();

        if (false === $storage->isExists($file))
        {
            ExceptionHelper::create(new FileNotFoundException())
                ->message('Can\'t remove file at path [%s]. File does not exist on storage [%s]', $file->getPath(), $file->getStorageClass())
                ->throw()
            ;
        }

        if ($file->isOpen())
        {
            $storage->close($file);
        }

        $filePath = $storage->getFullPath($file);

        if (false === unlink($filePath))
        {
            ExceptionHelper::create(new UnableToRemoveFileException())
                ->message('Unable to remove file at path [%s], ensure what file exist and you have all of needed permissions', $filePath)
                ->throw();
        }

        return $file;
    }
}