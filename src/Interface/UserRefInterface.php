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

use LetsCompose\Core\Object\Tools\PropertyList;

interface UserRefInterface
{
    public function getRef(): string|int|null;

    public function getProperties(): PropertyList;
    public function setProperties(PropertyList $properties): self;
}