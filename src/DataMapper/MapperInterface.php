<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\DataMapper;

interface MapperInterface
{
    public static function create(array $mappingConfig): self;

    public function map(string $configPath, object|array $data): object|array;
}