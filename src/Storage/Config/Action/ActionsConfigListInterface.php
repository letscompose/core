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
interface ActionsConfigListInterface
{
    public function addAction(ActionConfigInterface $action): self;

    public function getAction(string $name): ActionConfigInterface;

    public function hasAction(string $name): bool;

    public function hasActions(): bool;
}