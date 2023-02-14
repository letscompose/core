<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace LetsCompose\Core\HttpClient\Config;

use LetsCompose\Core\HttpClient\Config\Action\ActionConfigInterface;

interface ClintConfigInterface extends ConfigInterface
{
    public function setActions(array $actions): self;

    /**
     * @return ActionConfigInterface[]
     */
    public function getActions(): array;
}