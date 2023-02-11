<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Parser\Loader;

use LetsCompose\Core\Parser\Config\SourceImportConfigInterface;

interface ContentLoaderInterface
{
    public function load(string $source): array;
    public function supports(string $source): bool;
    public function setConfig(array $config): self;
    public function getConfig(): array;
}