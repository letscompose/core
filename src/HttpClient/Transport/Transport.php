<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace LetsCompose\Core\HttpClient\Transport;

use LetsCompose\Core\HttpClient\Config\Action\ActionsConfig;
use LetsCompose\Core\HttpClient\Config\Response\ResponseConfigInterface;
use LetsCompose\Core\HttpClient\Config\ResponseException\ExceptionConfigListInterface;
use LetsCompose\Core\HttpClient\Request\RequestInterface;
use LetsCompose\Core\HttpClient\Response\Response;
use LetsCompose\Core\HttpClient\Response\ResponseInterface;

class Transport implements TransportInterface
{
    private ResponseConfigInterface $responseConfig;
    private ExceptionConfigListInterface $exceptionConfigList;
    public function send(RequestInterface $request): ResponseInterface
    {
        return new Response();
    }

    public function setResponseConfig(ActionsConfig $responseConfig): self
    {
        $this->responseConfig = $responseConfig;
        return $this;
    }

    public function setResponseExceptionConfig(ExceptionConfigListInterface $exceptionConfigList): TransportInterface
    {
        $this->exceptionConfigList = $exceptionConfigList;
        return $this;
    }

}