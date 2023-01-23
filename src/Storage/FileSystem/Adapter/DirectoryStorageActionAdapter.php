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
use LetsCompose\Core\Storage\FileSystem\Resource\Action\Directory\CloseAction;
use LetsCompose\Core\Storage\FileSystem\Resource\Action\Directory\CreateAction;
use LetsCompose\Core\Storage\FileSystem\Resource\Action\Directory\IsExistsAction;
use LetsCompose\Core\Storage\FileSystem\Resource\Action\Directory\IsReadableAction;
use LetsCompose\Core\Storage\FileSystem\Resource\Action\Directory\IsWritableAction;
use LetsCompose\Core\Storage\FileSystem\Resource\Action\Directory\OpenAction;
use LetsCompose\Core\Storage\FileSystem\Resource\Action\Directory\ReadAction;
use LetsCompose\Core\Storage\FileSystem\Resource\Action\Directory\GetInfoAction;
use LetsCompose\Core\Storage\FileSystem\Resource\Directory;
use LetsCompose\Core\Storage\StorageInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class DirectoryStorageActionAdapter extends AbstractCompositeAdapter
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
            IsExistsAction::class,
            IsWritableAction::class,
            IsReadableAction::class,
            GetInfoAction::class,
        ];
        $this->registerActions($actions);

        parent::__construct($storage);
    }

    public function getSupportedResource(): string
    {
        return Directory::class;
    }
}