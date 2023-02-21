<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\DataMapper\Service;

abstract class AbstractServiceConfig implements ServiceConfigInterface
{
    private ?string $class = null;

    private array $config = [];

    private int $priority = 0;

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(?string $class): ServiceConfig
    {
        $this->class = $class;
        return $this;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function setConfig(array $config): ServiceConfig
    {
        $this->config = $config;
        return $this;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): ServiceConfig
    {
        $this->priority = $priority;
        return $this;
    }
}