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
    private array $requestOptions = [];

    /**
     * @var OptionConfig[]
     */
    private array $responseOptions = [];

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
    public function getRequestOptions(): array
    {
        return $this->requestOptions;
    }

    /**
     * @param array $requestOptions
     * @return ClientConfig
     */
    public function setRequestOptions(array $requestOptions): ClientConfig
    {
        $this->requestOptions = $requestOptions;
        return $this;
    }

    /**
     * @return array
     */
    public function getResponseOptions(): array
    {
        return $this->responseOptions;
    }

    /**
     * @param array $responseOptions
     * @return ClientConfig
     */
    public function setResponseOptions(array $responseOptions): ClientConfig
    {
        $this->responseOptions = $responseOptions;
        return $this;
    }
}