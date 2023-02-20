<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace LetsCompose\Core\HttpClient\Config\Option;

class OptionConfig extends AbstractObjectConfig implements OptionConfigInterface
{
    private string $name;

    private ?OptionLoaderConfigInterface $loaderConfig = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): OptionConfig
    {
        $this->name = $name;
        return $this;
    }

    public function getLoaderConfig(): ?OptionLoaderConfigInterface
    {
        return $this->loaderConfig;
    }

    public function setLoaderConfig(?OptionLoaderConfigInterface $loaderConfig): OptionConfig
    {
        $this->loaderConfig = $loaderConfig;
        return $this;
    }

    public function hasLoaderConfig(): bool
    {
        return $this->loaderConfig instanceof OptionLoaderConfigInterface;
    }
}