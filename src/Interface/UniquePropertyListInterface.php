<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Interface;

interface UniquePropertyListInterface
{
    public function get(string $name): PropertyInterface;
    public function set(string $name, mixed $value): self;
    public function has(string $name): bool;
    public function remove(string $name): bool;
}