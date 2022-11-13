<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Tools\Storage;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
interface FileLocatorInterface
{

    /**
     * locate file by file path.
     * if file exist return full file path otherwise throw an exception
     *
     * @param string $filePath
     * @return mixed
     */
    public function locate(string $filePath);
}