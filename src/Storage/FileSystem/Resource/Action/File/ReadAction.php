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

use LetsCompose\Core\Storage\Actions\ActionInterface;
use LetsCompose\Core\Storage\FileSystem\Resource\FileInterface;
use LetsCompose\Core\Storage\Resource\ResourceInterface;

class ReadAction implements ActionInterface
{
    public function execute(ResourceInterface $resource, ...$params): string
    {
        return $this->read($resource, ...$params);
    }

    private function read(FileInterface $file, int $chunkSize = 0): string
    {
        dump($file->getPath(), $chunkSize);
        die();
        return 'hello';
    }

}