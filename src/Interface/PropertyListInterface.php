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

interface PropertyListInterface
{
    public function getOne(string $name): PropertyInterface;

    /**
     * @return PropertyInterface[]
     */
    public function get(string $name): array;
    public function has(string $name): bool;
    public function add(string $name, mixed $value): self;
    public function remove(PropertyInterface $property): bool;
    public function clear(): self;
    public static function createFromArray(array $properties): self;
}