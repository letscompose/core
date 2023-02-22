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
use LetsCompose\Core\HttpClient\Config\Response\ResponseConfigInterface;
use LetsCompose\Core\HttpClient\Config\ResponseException\ExceptionConfigListInterface;
use LetsCompose\Core\HttpClient\Request\Request;
use LetsCompose\Core\HttpClient\Request\RequestInterface;
use LetsCompose\Core\HttpClient\Response\ResponseInterface;
use LetsCompose\Core\HttpClient\Transport\TransportInterface;
use LetsCompose\Core\HttpClient\Transport\TransportResponseInterface;

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

    /**
     * @throws \Exception
     */
    public function send(RequestInterface $request): ResponseInterface
    {
        $actionConfig = $this->config->getAction($request->getPath());
        $request = $this->applyRequestOptions($request);

        try
        {
            $transportResponse = $this->transport->send($request);
        }
        catch (\Exception $exception)
        {
            throw $this->createException($exception, $actionConfig->getResponseExceptionConfig());
        }

        // TODO check exception conditions. HttpClient must not throw an error exception on 400 - 500 http code range


        $response = $this->createResponse($transportResponse, $actionConfig->getResponseConfig());

        $response = $this->applyResponseOptions($response);
        return $response;
    }

    protected function applyRequestOptions(RequestInterface $request): RequestInterface
    {
        return $request;
    }

    protected function applyResponseOptions(ResponseInterface $response): ResponseInterface
    {
        return $response;
    }

    protected function createResponse(TransportResponseInterface $transportResponse, ResponseConfigInterface $responseConfig): ResponseInterface
    {

    }

    protected function createException(\Exception $e, ExceptionConfigListInterface $responseExceptionConfig): \Exception
    {
        return new \Exception();
    }

}