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
    public function load(SourceImportConfigInterface $config): array;
    public function supports(SourceImportConfigInterface $config): bool;
}