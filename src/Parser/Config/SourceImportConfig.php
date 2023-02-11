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

class SourceImportConfig implements SourceImportConfigInterface
{
    private string $source;

    private ?SourceLoaderConfigInterface $loader = null;

    public function getSource(): string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;
        return $this;
    }

    public function hasLoaderConfig(): bool
    {
        return $this->loader instanceof SourceLoaderConfigInterface;
    }

    public function getSourceLoaderConfig(): ?SourceLoaderConfigInterface
    {
        return $this->loader;
    }

    public function setSourceLoaderConfig(?SourceLoaderConfigInterface $loaderConfig): self
    {
        $this->loader = $loaderConfig;
        return $this;
    }
}