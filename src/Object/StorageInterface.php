<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Object;

interface StorageInterface
{
    public static function create(): self;

    public function put(object $object): self;

    public function get(object $object): self;

    public function has(object $object): bool;

    public function remove(object $object): self;

}