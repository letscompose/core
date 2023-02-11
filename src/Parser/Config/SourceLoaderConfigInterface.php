<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Parser\Config;

interface SourceLoaderConfigInterface
{
    public function getClass(): string;

    public function setClass(string $class): self;

    public function getConfig(): array;
    public function setConfig(array $config): self;
}