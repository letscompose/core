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

class Storage
{
    private \SplObjectStorage $list;

    public static function create(): StorageInterface
    {
        return new self();
    }



}