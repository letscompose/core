<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\FileSystem\Resource\Action\Directory;

use LetsCompose\Core\Storage\Actions\AbstractAction;
use LetsCompose\Core\Storage\Exception\DirectoryNotFoundException;
use LetsCompose\Core\Storage\FileSystem\Resource\DirectoryInterface;
use LetsCompose\Core\Tools\ExceptionHelper;

class CreateAction extends AbstractAction
{
    protected const STORAGE_METHOD  = 'create';

    protected function create(DirectoryInterface $directory): DirectoryInterface
    {
        $storage = $this->getStorage();
        $path = $storage->getFullPath($directory);

        if (false === $storage->isWritable($directory))
        {
            ExceptionHelper::create(new DirectoryNotFoundException())
                ->message('Can\'t write to directory at path [%s]. Directory not writable on storage [%s]', $directory->getPath(), $directory->getStorageClass())
                ->throw()
            ;
        }

        mkdir(directory: $path, recursive: true);
        return $directory;
    }
}