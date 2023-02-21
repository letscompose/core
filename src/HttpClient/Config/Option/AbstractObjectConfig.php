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

use LetsCompose\Core\HttpClient\Config\ConfigInterface;

abstract class AbstractObjectConfig
{
    private string $class;

    private mixed $config = null;

    private ?int $priority = null;

    public function getClass(): string
    {
        return $this->class;
    }

    public function setClass(string $class): AbstractObjectConfig
    {
        $this->class = $class;
        return $this;
    }

    public function getConfig(): mixed
    {
        return $this->config;
    }

    public function setConfig(mixed $config): AbstractObjectConfig
    {
        $this->config = $config;
        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(?int $priority): AbstractObjectConfig
    {
        $this->priority = $priority;
        return $this;
    }
}