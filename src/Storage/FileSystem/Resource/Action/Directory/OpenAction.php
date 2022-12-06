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

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\InvalidArgumentException;
use LetsCompose\Core\Storage\Actions\AbstractAction;
use LetsCompose\Core\Storage\Exception\DirectoryNotFoundException;
use LetsCompose\Core\Storage\Exception\DirectoryNotReadableException;
use LetsCompose\Core\Storage\FileSystem\Resource\DirectoryInterface;
use LetsCompose\Core\Tools\ExceptionHelper;

class OpenAction extends AbstractAction
{
    protected const STORAGE_METHOD  = 'open';

    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    protected function open(DirectoryInterface $directory): DirectoryInterface
    {
        if ($directory->isOpen())
        {
            return $directory;
        }

        $storage = $this->getStorage();
        if (false === $storage->isExists($directory))
        {
            ExceptionHelper::create(new DirectoryNotFoundException())
                ->message('Can\'t open directory at path [%s]. Directory does not exist on storage [%s]', $directory->getPath(), $directory->getStorageClass())
                ->throw()
            ;
        }

        if (false === $storage->isReadable($directory))
        {
            ExceptionHelper::create(new DirectoryNotReadableException())
                ->message('Not readable directory at path [%s] on storage [%s]. Have you all of needed permissions ?', $directory->getPath(), $directory->getStorageClass())
                ->throw()
            ;
        }

        $fullPath = $storage->getFullPath($directory);

        $stream = opendir($fullPath);
        return $directory->setStream($stream);
    }
}