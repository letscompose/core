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

interface SourceImportConfigInterface
{
    public function getSource(): string;

    public function setSource(string $source): self;

    public function hasLoader(): bool;

    public function getLoader(): ?string;

    public function setLoader(?string $loader): self;
}