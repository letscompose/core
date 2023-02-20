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
use LetsCompose\Core\HttpClient\Config\Option\OptionConfig;
use LetsCompose\Core\HttpClient\Config\Option\OptionConfigInterface;

class ClientConfig implements ClientConfigInterface
{
    /**
     * @var ActionConfigInterface[]
     */
    private array $actions;

    /**
     * @var OptionConfig[]
     */
    private array $options = [];

    /**
     * @return array
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * @param array $actions
     * @return ClientConfig
     */
    public function setActions(array $actions): ClientConfig
    {
        $this->actions = $actions;
        return $this;
    }

    public function getAction(string $name): ActionConfigInterface
    {
        return $this->actions[$name];
    }

    public function hasAction(string $name): bool
    {
        return isset($this->actions[$name]);
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return ClientConfig
     */
    public function setOptions(array $options): ClientConfig
    {
        $this->options = $options;
        return $this;
    }

    public function getOption(string $name): OptionConfigInterface
    {
       return $this->options[$name];
    }

    public function hasOption(string $name): bool
    {
        return isset($this->options[$name]);
    }


}