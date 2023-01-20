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

interface PropertyInterface
{
    public function getName(): string;
    public function setName(string $name): self;
    public function getValue(): mixed;
    public function setValue(mixed $value): self;
}