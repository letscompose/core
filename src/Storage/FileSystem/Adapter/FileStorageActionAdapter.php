<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\FileSystem\Adapter;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\MustImplementException;
use LetsCompose\Core\Storage\Adapter\AbstractCompositeAdapter;
use LetsCompose\Core\Storage\FileSystem\Resource\Action\File\AppendAction;
use LetsCompose\Core\Storage\FileSystem\Resource\Action\File\CloseAction;
use LetsCompose\Core\Storage\FileSystem\Resource\Action\File\CreateAction;
use LetsCompose\Core\Storage\FileSystem\Resource\Action\File\FlushAction;
use LetsCompose\Core\Storage\FileSystem\Resource\Action\File\IsExistsAction;
use LetsCompose\Core\Storage\FileSystem\Resource\Action\File\IsReadableAction;
use LetsCompose\Core\Storage\FileSystem\Resource\Action\File\OpenAction;
use LetsCompose\Core\Storage\FileSystem\Resource\Action\File\ReadAction;
use LetsCompose\Core\Storage\FileSystem\Resource\Action\File\ReadLineAction;
use LetsCompose\Core\Storage\FileSystem\Resource\Action\File\RemoveAction;
use LetsCompose\Core\Storage\FileSystem\Resource\Action\File\WriteAction;
use LetsCompose\Core\Storage\FileSystem\Resource\File;
use LetsCompose\Core\Storage\StorageInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class FileStorageActionAdapter extends AbstractCompositeAdapter
{
    /**
     * @throws MustImplementException
     * @throws ExceptionInterface
     */
    public function __construct(StorageInterface $storage)
    {
        $actions = [
            CreateAction::class,
            OpenAction::class,
            CloseAction::class,
            ReadAction::class,
            ReadLineAction::class,
            RemoveAction::class,
            FlushAction::class,
            IsExistsAction::class,
            IsReadableAction::class,
            WriteAction::class,
            AppendAction::class
        ];
        $this->registerActions($actions);

        parent::__construct($storage);
    }

    public function getSupportedResource(): string
    {
        return File::class;
    }
}