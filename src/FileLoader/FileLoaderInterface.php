<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\FileLoader;

interface FileLoaderInterface
{
    public static function getInstance(string $path): self;

    public function getContents(string $path): string;

    public function getPath(): string;

    public function supports(string $path): bool;
}