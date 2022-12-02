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

class ReadAction extends AbstractAction
{
    const STORAGE_METHOD  = 'initResource';

    public function execute(...$params): string
    {
        return $this->read(...$params);
    }

    private function read($path): string
    {
        $this->getStorage()->read($path);

        return $path;
    }
}