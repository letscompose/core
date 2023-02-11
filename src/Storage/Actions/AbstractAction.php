<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\Actions;


use LetsCompose\Core\Storage\ResourceStorageInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
abstract class AbstractAction implements ActionInterface
{
    private ResourceStorageInterface $storage;

    public function setStorage(ResourceStorageInterface $storage): ActionInterface
    {
        $this->storage = $storage;
        return $this;
    }

    public function getStorage(): ResourceStorageInterface
    {
        return $this->storage;
    }

    public static function storageMethod(): string
    {
        return get_called_class()::STORAGE_METHOD;
    }

    public function execute(...$params): mixed
    {
        return $this->{self::storageMethod()}(...$params);
    }
}