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

use LetsCompose\Core\Storage\Actions\AbstractAction;
use LetsCompose\Core\Storage\FileSystem\Resource\DirectoryInterface;
use LetsCompose\Core\Storage\Resource\ResourceInterface;

class CloseAction extends AbstractAction
{
    protected const STORAGE_METHOD  = 'close';

    protected function close(DirectoryInterface $directory): DirectoryInterface
    {
        closedir($directory->getStream());
        $directory->setState(ResourceInterface::STATE_CLOSED_STREAM);
        return $directory;
    }
}