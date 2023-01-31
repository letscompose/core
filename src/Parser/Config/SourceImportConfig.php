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

    private ?string $loader = null;

    public function getSource(): string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;
        return $this;
    }

    public function hasLoader(): bool
    {
        return !empty($this->loader);
    }

    /**
     * @return string|null
     */
    public function getLoader(): ?string
    {
        return $this->loader;
    }

    /**
     * @param string|null $loader
     * @return SourceImportConfig
     */
    public function setLoader(?string $loader): SourceImportConfig
    {
        $this->loader = $loader;
        return $this;
    }
}