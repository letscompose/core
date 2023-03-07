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

interface ObjectConfigInterface extends ConfigInterface
{
    public function getClass(): string;
    public function setClass(string $class): self;
    public function getConfig(): mixed;
    public function setConfig(mixed $config): self;
    public function getPriority(): ?int;
    public function setPriority(?int $priority): self;
}