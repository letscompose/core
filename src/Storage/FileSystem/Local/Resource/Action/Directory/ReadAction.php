<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\FileSystem\Local\Resource\Action\Directory;

use Generator;
use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\InvalidArgumentException;
use LetsCompose\Core\Storage\Actions\AbstractAction;
use LetsCompose\Core\Storage\Exception\UnsupportedResourceException;
use LetsCompose\Core\Storage\FileSystem\Resource\Directory;
use LetsCompose\Core\Tools\ExceptionHelper;
use LetsCompose\Core\Tools\Storage\PathHelper;

class ReadAction extends AbstractAction
{
    protected const STORAGE_METHOD  = 'read';

    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    protected function read(Directory $directory): Generator|false
    {
        $storage = $this->getStorage();
        if (false === $directory->isOpen())
        {
            $directory = $storage->open($directory);
        }

        $storageRootPath = $storage->getRootPath();

        while (false !== ($path = readdir($directory->getStream())))
        {
            if ('.' === $path || '..' === $path)
            {
                continue;
            }
            $fullResourcePath = PathHelper::normalize(sprintf('%s/%s', $storageRootPath, $path));
            if (is_dir($fullResourcePath))
            {
                $resource = $storage->initDirectory($path);
            } elseif (is_file($fullResourcePath))
            {
                $resource = $storage->initFile($path);
            } else
            {
                ExceptionHelper::create(new UnsupportedResourceException())
                    ->setMessage('Unsupported storage resource [%s]', $fullResourcePath)
                    ->throw();
            }
            yield $resource;
        }

        rewinddir($directory->getStream());

        return false;
    }
}