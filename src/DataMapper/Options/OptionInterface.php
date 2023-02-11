<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\DataMapper\Options;

interface OptionInterface
{
    public function getName(): string;
    public function setName(string $name): self;
    public function process(array $data): array;
    public function setConfig(mixed $config): self;
    public function supports(string $name): bool;
}