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
use LetsCompose\Core\HttpClient\Config\Option\OptionConfigInterface;

interface ClientConfigInterface extends ConfigInterface
{
    /**
     * @param ActionConfigInterface[] $actions
     */
    public function setActions(array $actions): self;

    /**
     * @return ActionConfigInterface[]
     */
    public function getActions(): array;

    public function getAction(string $name): ActionConfigInterface;
    public function hasAction(string $name): bool;

    /**
     * @param OptionConfigInterface[] $options
     */
    public function setRequestOptions(array $options): self;

    /**
     * @param OptionConfigInterface[] $options
     */
    public function setResponseOptions(array $options): self;

    /**
     * @return OptionConfigInterface[]
     */
    public function getRequestOptions(): array;

    /**
     * @return OptionConfigInterface[]
     */
    public function getResponseOptions(): array;
}