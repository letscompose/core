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

use LetsCompose\Core\Storage\Adapter\AbstractCompositeAdapter;
use LetsCompose\Core\Storage\FileSystem\Resource\Action\File\ReadAction;
use LetsCompose\Core\Storage\FileSystem\Resource\File;
use LetsCompose\Core\Storage\StorageInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class FileStorageActionAdapter extends AbstractCompositeAdapter
{
    public function __construct(StorageInterface $storage)
    {
        $actions = [
           ReadAction::class,
        ];
        $this->registerActions($actions);

        parent::__construct($storage);
    }

    public function isResourceSupported(string $resourceClass): bool
    {
        return $resourceClass === $this->getSupportedResource();
    }

    public function getSupportedResource(): string
    {
        return File::class;
    }
}