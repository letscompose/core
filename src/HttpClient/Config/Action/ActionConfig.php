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
    private string $path;

    private RequestConfigInterface $requestConfig;

    private ?ResponseConfigInterface $responseConfig;

    private ?ExceptionConfigList $responseExceptionConfig;

    public function getPath(): string
    {
        return $this->path;
    }
    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    public function getRequestConfig(): RequestConfigInterface
    {
        return $this->requestConfig;
    }

    public function setRequestConfig(RequestConfigInterface $requestConfig): self
    {
        $this->requestConfig = $requestConfig;
        return $this;
    }

    public function getResponseConfig(): ?ResponseConfigInterface
    {
        return $this->responseConfig;
    }

    public function setResponseConfig(?ResponseConfigInterface $responseConfig): self
    {
        $this->responseConfig = $responseConfig;
        return $this;
    }

    public function getResponseExceptionConfig(): ?ExceptionConfigList
    {
        return $this->responseExceptionConfig;
    }

    public function setResponseExceptionConfig(?ExceptionConfigList $responseExceptionConfig): self
    {
        $this->responseExceptionConfig = $responseExceptionConfig;
        return $this;
    }
}