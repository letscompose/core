<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage;

use phpDocumentor\Reflection\Types\Resource_;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
interface FileSystemStorageInterface
{
    /**
     * method must open file and return a resource
     * @return mixed
     */
    public function read(): mixed;

    /**
     * check if file exist
     * @return bool
     */
    public function fileExists(): bool;
}