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
use LetsCompose\Core\Storage\Resource\ResourceInterface;

class IsExistsAction extends AbstractAction
{
    protected const STORAGE_METHOD  = 'isExists';

    protected function isExists(ResourceInterface $resource): bool
    {
        $fullFilePath = $this->getStorage()->getFullPath($resource);
        return file_exists($fullFilePath) && is_dir($fullFilePath);
    }
}