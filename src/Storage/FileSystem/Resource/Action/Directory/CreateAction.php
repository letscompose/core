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
use LetsCompose\Core\Storage\FileSystem\Resource\Directory;

class CreateAction extends AbstractAction
{
    protected const STORAGE_METHOD  = 'create';

    protected function create(Directory $directory): Directory
    {
        $storage = $this->getStorage();
        $path = $storage->getFullPath($directory);

        mkdir(directory: $path, recursive: true);
        return $directory;
    }
}