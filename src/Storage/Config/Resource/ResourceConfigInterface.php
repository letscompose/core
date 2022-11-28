<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\Config\Resource;

use LetsCompose\Core\Storage\Config\ConfigInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
interface ResourceConfigInterface extends ConfigInterface
{
    public function setActionNameSpace(?string $actionNameSpace): self;

    public function getActionNameSpace(): ?string;

    public function setStorageClass(string $class): self;

    public function getStorageClass(): string;
}