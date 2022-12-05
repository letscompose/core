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

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Storage\Actions\AbstractAction;
use LetsCompose\Core\Storage\Exception\FileAlreadyExistException;
use LetsCompose\Core\Storage\FileSystem\Enum\FileOpenModeEnum;
use LetsCompose\Core\Storage\FileSystem\Resource\Directory;
use LetsCompose\Core\Storage\FileSystem\Resource\FileInterface;
use LetsCompose\Core\Storage\Resource\ResourceInterface;
use LetsCompose\Core\Tools\ExceptionHelper;

class CreateAction extends AbstractAction
{
    protected const STORAGE_METHOD  = 'create';

    /**
     * @throws FileAlreadyExistException
     * @throws ExceptionInterface
     */
    protected function create(ResourceInterface $file): FileInterface
    {
        $storage = $this->getStorage();
        $fullFilePath = $storage->getFullPath($file);

        if ($storage->isExists($file))
        {
            ExceptionHelper::create(new FileAlreadyExistException())
                ->message('You try to create already existing file [%s]', $fullFilePath)
                ->throw();
        }

        /**
         * @var Directory $directory
         */
        $directory = $storage->initDirectory($file->getDirectoryPath());

        if (false === $storage->isExists($directory))
        {
            $storage->create($directory);
        }

        touch($fullFilePath);
        return $storage->open($file, FileOpenModeEnum::WRITE);

    }
}