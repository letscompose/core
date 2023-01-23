<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\FileSystem\Local\Adapter;

use LetsCompose\Core\Storage\Adapter\AbstractCompositeAdapter;
use LetsCompose\Core\Storage\FileSystem\Local\Resource\Action\File\AppendAction;
use LetsCompose\Core\Storage\FileSystem\Local\Resource\Action\File\CloseAction;
use LetsCompose\Core\Storage\FileSystem\Local\Resource\Action\File\CreateAction;
use LetsCompose\Core\Storage\FileSystem\Local\Resource\Action\File\FlushAction;
use LetsCompose\Core\Storage\FileSystem\Local\Resource\Action\File\GetInfoAction;
use LetsCompose\Core\Storage\FileSystem\Local\Resource\Action\File\IsExistsAction;
use LetsCompose\Core\Storage\FileSystem\Local\Resource\Action\File\IsReadableAction;
use LetsCompose\Core\Storage\FileSystem\Local\Resource\Action\File\IsWritableAction;
use LetsCompose\Core\Storage\FileSystem\Local\Resource\Action\File\OpenAction;
use LetsCompose\Core\Storage\FileSystem\Local\Resource\Action\File\ReadAction;
use LetsCompose\Core\Storage\FileSystem\Local\Resource\Action\File\ReadLineAction;
use LetsCompose\Core\Storage\FileSystem\Local\Resource\Action\File\RemoveAction;
use LetsCompose\Core\Storage\FileSystem\Local\Resource\Action\File\WriteAction;
use LetsCompose\Core\Storage\FileSystem\Resource\File;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class FileStorageActionAdapter extends AbstractCompositeAdapter
{
    public function getActionsClassList(): array
    {
        return [
            CreateAction::class,
            OpenAction::class,
            CloseAction::class,
            ReadAction::class,
            ReadLineAction::class,
            RemoveAction::class,
            IsExistsAction::class,
            IsReadableAction::class,
            IsWritableAction::class,
            GetInfoAction::class,
            FlushAction::class,
            WriteAction::class,
            AppendAction::class,
        ];
    }


    public function getSupportedResource(): string
    {
        return File::class;
    }
}