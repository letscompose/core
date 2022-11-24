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

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
interface ResourceConfigInterface
{
    public function setClass(string $class): self;

    public function getClass(): string;

    public function setActionNameSpace(?string $actionNameSpace): self;

    public function getActionNameSpace(): ?string;
}