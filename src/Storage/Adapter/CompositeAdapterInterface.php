<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\Adapter;

use LetsCompose\Core\Storage\Actions\ActionInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
interface CompositeAdapterInterface extends AdapterInterface
{
    /**
     * @return static[]
     */
    public function getActionsClassList(): array;

    public function registerActions(array $actionsClassList): self;

    public function hasAction(string $actionName): bool;

    public function getAction(string $actionName): ActionInterface;
}