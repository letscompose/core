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

use LetsCompose\Core\Storage\Resource\ResourceInterface;
use LetsCompose\Core\Storage\ResourceStorageInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
interface ActionInterface
{
    public function execute(...$params): mixed;

    public function setStorage(ResourceStorageInterface $storage): self;

    public function getStorage(): ResourceStorageInterface;

    public static function storageMethod(): string;
}