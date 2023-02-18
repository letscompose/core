<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace LetsCompose\Core\HttpClient\Config\Action;

use LetsCompose\Core\HttpClient\Config\Request\RequestConfigInterface;
use LetsCompose\Core\HttpClient\Config\Response\ResponseConfigInterface;
use LetsCompose\Core\HttpClient\Config\ResponseException\ExceptionConfigList;

class ActionConfig implements ActionConfigInterface
{
    private RequestConfigInterface $requestConfig;

    private ?ResponseConfigInterface $responseConfig;

    private ?ExceptionConfigList $responseExceptionConfig;

    public function getRequestConfig(): RequestConfigInterface
    {
        return $this->requestConfig;
    }

    public function setRequestConfig(RequestConfigInterface $requestConfig): ActionConfig
    {
        $this->requestConfig = $requestConfig;
        return $this;
    }

    public function getResponseConfig(): ?ResponseConfigInterface
    {
        return $this->responseConfig;
    }

    public function setResponseConfig(?ResponseConfigInterface $responseConfig): ActionConfig
    {
        $this->responseConfig = $responseConfig;
        return $this;
    }

    public function getResponseExceptionConfig(): ?ExceptionConfigList
    {
        return $this->responseExceptionConfig;
    }

    public function setResponseExceptionConfig(?ExceptionConfigList $responseExceptionConfig): ActionConfig
    {
        $this->responseExceptionConfig = $responseExceptionConfig;
        return $this;
    }
}