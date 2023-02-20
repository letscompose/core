<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace LetsCompose\Core\HttpClient;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\NotExistsException;
use LetsCompose\Core\HttpClient\Config\ClientConfigInterface;
use LetsCompose\Core\HttpClient\Request\Request;
use LetsCompose\Core\HttpClient\Request\RequestInterface;
use LetsCompose\Core\HttpClient\Response\ResponseInterface;
use LetsCompose\Core\HttpClient\Transport\TransportInterface;

class HttpClient implements HttpClientInterface
{
    private ClientConfigInterface $config;

    public function __construct(private readonly TransportInterface $transport)
    {
    }


    public function loadConfig(string $configFile): self
    {
        //TODO implements loadConfig
        //TODO implements call configure
    }

    public function setConfig(ClientConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @throws ExceptionInterface
     */
    public function createRequest(string $requestPath): RequestInterface
    {
        if (false === $this->config->hasAction($requestPath))
        {
            throw (new NotExistsException())
                ->setMessage('HttpClient request [%s] doest not exists', $requestPath);
        }
        return new Request($this->config->getAction($requestPath)->getRequestConfig());
    }

    public function send(RequestInterface $request): ResponseInterface
    {
        $this->applyRequestOptions($request);

        $response = $this->transport
            ->setResponseConfig()
            ->setResponseExceptionConfig()
            ->send($request);

        $response = $this->applyResponseOptions($response);
        return $response;
    }

    protected function applyRequestOptions(RequestInterface $request): RequestInterface
    {

    }

    protected function applyResponseOptions(ResponseInterface $response): ResponseInterface
    {

    }

}