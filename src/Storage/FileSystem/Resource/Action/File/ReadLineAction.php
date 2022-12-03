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

use Generator;
use LetsCompose\Core\Storage\Actions\AbstractAction;
use LetsCompose\Core\Storage\FileSystem\Resource\FileInterface;
use LetsCompose\Core\Storage\Resource\ResourceInterface;

class ReadLineAction extends AbstractAction
{
    protected const STORAGE_METHOD  = 'readLine';

    protected function readLine(FileInterface|ResourceInterface $file): Generator
    {
        $storage = $this->getStorage();
        $line = null;
        while ($data = $storage->read($file))
        {
            $i = 0;
            $length = strlen($data)-1;
            while ($i < $length)
            {
                $line .= $data[$i++];
                if ($line[-1] === PHP_EOL)
                {
                    yield $line;
                    $line = null;
                }
            }
        }
        yield $line;
    }
}