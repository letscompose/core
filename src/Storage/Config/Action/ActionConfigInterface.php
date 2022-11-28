<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\Config;

use LetsCompose\Core\Storage\Resource\ResourceInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
interface ActionConfigInterface extends ConfigInterface
{
    public function setName(string $name): self;

    public function getName(): string;

    public function setNameSpace(string $nameSpace): self;

    public function getNameSpace(): string;

    public function setStorageClass(string $storageClass): self;

    public function getStorageClass(): string;

    public function setResourceClass(string $resourceClass): self;

    public function getResourceClass(): string;
}