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
use LetsCompose\Core\HttpClient\Config\Action\ActionConfigInterface;
use LetsCompose\Core\HttpClient\Config\ClientConfigInterface;
use LetsCompose\Core\HttpClient\Config\Response\ResponseCodeHelper;
use LetsCompose\Core\HttpClient\Config\ResponseException\ExceptionConfig;
use LetsCompose\Core\HttpClient\Config\ResponseException\ExceptionConfigInterface;
use LetsCompose\Core\HttpClient\Config\ResponseException\ExceptionConfigListInterface;
use LetsCompose\Core\HttpClient\Exception\TransportException;
use LetsCompose\Core\HttpClient\Request\Request;
use LetsCompose\Core\HttpClient\Request\RequestInterface;
use LetsCompose\Core\HttpClient\Response\ResponseInterface;
use LetsCompose\Core\HttpClient\Response\Response;
use LetsCompose\Core\HttpClient\Transport\TransportInterface;
use LetsCompose\Core\HttpClient\Transport\TransportResponse;
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
        try {
            $transportResponse = $this->transport->send($request);
        } catch (\Throwable $exception)
        {
            $transportResponse = new TransportResponse
            (
                $exception->getCode(),
                null,
                null,
                $exception
            );
        }

        $response = $this->createResponse($transportResponse, $actionConfig);

        $content = $response->getContent();

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
        $responseStatusCode = $transportResponse->getStatusCode();
        if (!ResponseCodeHelper::isHttpResponseCode($responseStatusCode))
        {
            throw (new TransportException())
                ->setMessage(
                    'Unknown http response status code [%s] returned by request [%s]',
                    $responseStatusCode,
                    $actionConfig->getPath()
                );
        }

        $exception = null;
        if (false === ResponseCodeHelper::isSuccessful($responseStatusCode) )
        {
            $exception = $this->createException($transportResponse, $actionConfig->getResponseExceptionConfig());
        }

        return (new Response())
            ->setStatusCode($responseStatusCode)
            ->setExceptionConfig($exception)
            ->setHeaders($transportResponse->getHeaders())
            ->setContent($transportResponse->getContent());
    }

    protected function createException(TransportResponseInterface $transportResponse, ExceptionConfigListInterface $responseExceptionConfig): ?ExceptionConfigInterface
    {
        $responseCode = $transportResponse->getStatusCode();

        $mute = $responseExceptionConfig->getMute();
        if (true === $mute || (is_array($mute) && in_array($responseCode, $mute)))
        {
            return null;
        }

        $exceptionConfig = $responseExceptionConfig->getExceptionConfigByRaiseWhenResponseCode([$responseCode]);

        $exceptionConfig = $exceptionConfig
            ?: $responseExceptionConfig->getDefaultExceptionConfig()
            ?: (new ExceptionConfig())
                ->setClass(TransportException::class)
        ;

        $exceptionMessage = trim(sprintf('%s %s', $exceptionConfig->getMessagePrefix(), $exceptionConfig->getMessage()));
        $exceptionMessage = sprintf('[%s] request exception: [%s]', $responseExceptionConfig->getPath(), $exceptionMessage);

        $exceptionConfig->setMessage($exceptionMessage);
        $exceptionConfig->setCode
            (
                $responseExceptionConfig->getCode() ?? $responseCode
            );


        if ($transportResponse->hasException())
        {
            $exceptionConfig->setPrevious($transportResponse->getException());
        }

        return $exceptionConfig;
    }

}