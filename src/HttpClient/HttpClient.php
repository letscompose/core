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
use LetsCompose\Core\HttpClient\Config\Action\ActionConfig;
use LetsCompose\Core\HttpClient\Config\Action\ActionConfigInterface;
use LetsCompose\Core\HttpClient\Config\ClientConfigInterface;
use LetsCompose\Core\HttpClient\Config\Response\ResponseCodeHelper;
use LetsCompose\Core\HttpClient\Config\Response\ResponseConfigInterface;
use LetsCompose\Core\HttpClient\Config\ResponseException\ExceptionConfig;
use LetsCompose\Core\HttpClient\Config\ResponseException\ExceptionConfigListInterface;
use LetsCompose\Core\HttpClient\Exception\TransportException;
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
     * @throws ExceptionInterface
     */
    public function send(RequestInterface $request): ResponseInterface
    {
        $actionConfig = $this->config->getAction($request->getPath());
        $request = $this->applyRequestOptions($request);
        $transportResponse = $this->transport->send($request);


        $response = $this->createResponse($transportResponse, $actionConfig);

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

    /**
     * @throws ExceptionInterface
     */
    protected function createResponse(TransportResponseInterface $transportResponse, ActionConfigInterface $actionConfig): ResponseInterface
    {
        $responseCode = $transportResponse->getStatusCode();
        if (!ResponseCodeHelper::isHttpResponseCode($responseCode))
        {
            throw (new TransportException())
                ->setMessage(
                    'Unknown http response status code [%s] returned by request [%s]',
                    $responseCode,
                    $actionConfig->getPath()
                );
        }

        $exception = null;
        if (false === ResponseCodeHelper::isSuccessful($responseCode) )
        {
            $exception = $this->createException($transportResponse, $actionConfig->getResponseExceptionConfig());
        }

        dump($exception);
        die;

    }

    protected function createException(TransportResponseInterface $transportResponse, ExceptionConfigListInterface $responseExceptionConfig): ?ExceptionConfig
    {
        $responseCode = $transportResponse->getStatusCode();

        $mute = $responseExceptionConfig->getMute();
        if ((true === $mute) || is_array($mute) && in_array($responseCode, $mute))
        {
            return null;
        }

        $exceptionConfig = $responseExceptionConfig->getExceptionConfigByRaiseWhenResponseCode([$responseCode]);
        if ($exceptionConfig)
        {
            return $exceptionConfig;
        }

        $defaultException = $responseExceptionConfig->getDefaultExceptionConfig();
        if (!$defaultException)
        {
            $defaultException = new ExceptionConfig();
            $defaultException->setMessagePrefix
            (
                $responseExceptionConfig->getMessagePrefix()
                    ?? sprintf('[%s]', $responseExceptionConfig->getPath())
            );
            $defaultException->setMessage
            (
                $responseExceptionConfig->getMessage() ?? 'Invalid API response'
            );
            $defaultException->setCode
            (
                $responseExceptionConfig->getCode() ?? $responseCode
            );
        }

        return $defaultException;
    }

}