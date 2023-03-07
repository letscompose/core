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

use Exception;
use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\NotExistsException;
use LetsCompose\Core\HttpClient\Config\Action\ActionConfigInterface;
use LetsCompose\Core\HttpClient\Config\ClientConfigInterface;
use LetsCompose\Core\HttpClient\Config\Option\OptionConfigInterface;
use LetsCompose\Core\HttpClient\Config\Response\ResponseCodeHelper;
use LetsCompose\Core\HttpClient\Config\ResponseException\ExceptionConfig;
use LetsCompose\Core\HttpClient\Config\ResponseException\ExceptionConfigInterface;
use LetsCompose\Core\HttpClient\Config\ResponseException\ExceptionConfigListInterface;
use LetsCompose\Core\HttpClient\Exception\TransportException;
use LetsCompose\Core\HttpClient\Option\OptionLoaderInterface;
use LetsCompose\Core\HttpClient\Option\RequestOptionInterface;
use LetsCompose\Core\HttpClient\Option\ResponseOptionInterface;
use LetsCompose\Core\HttpClient\Request\Request;
use LetsCompose\Core\HttpClient\Request\RequestInterface;
use LetsCompose\Core\HttpClient\Response\ResponseContent;
use LetsCompose\Core\HttpClient\Response\ResponseInterface;
use LetsCompose\Core\HttpClient\Response\Response;
use LetsCompose\Core\HttpClient\Transport\TransportInterface;
use LetsCompose\Core\HttpClient\Transport\TransportResponse;
use LetsCompose\Core\HttpClient\Transport\TransportResponseInterface;
use Throwable;

class HttpClient implements HttpClientInterface
{
    private ClientConfigInterface $config;

    /**
     * @var RequestOptionInterface[]
     */
    private array $requestOptions = [];

    /**
     * @var ResponseOptionInterface[]
     */
    private array $responseOptions = [];

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
        $this->requestOptions = $this->createOptions($config->getRequestOptions());
        $this->responseOptions = $this->createOptions($config->getRequestOptions());
        $this->config = $config;
    }

    /**
     * @var OptionConfigInterface[] $optionsConfigList
     */
    public function createOptions(array $optionsConfigList): array
    {
        $result = [];
        foreach ($optionsConfigList as $config)
        {
            $optionClass = $config->getClass();
            $optionConfig = $config->getConfig();

            if ($config->hasLoaderConfig())
            {
                $loaderConfig = $config->getLoaderConfig();
                /**
                 * @var OptionLoaderInterface $loader
                 */
                $loader = new ($loaderConfig->getClass())($loaderConfig->getConfig());

                $option = $loader->load($optionClass, $optionConfig);
            }
            else
            {
                $option = new ($optionClass)($optionConfig);
            }
            $result[$config->getName()] = $option;
        }

        return $result;
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
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function send(RequestInterface $request): ResponseInterface
    {
        $actionConfig = $this->config->getAction($request->getPath());
        $request = $this->applyRequestOptions($request);
        try {
            $transportResponse = $this->transport->send($request);
        } catch (Throwable $exception)
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

        return $this->applyResponseOptions($response);
    }

    protected function applyRequestOptions(RequestInterface $request): RequestInterface
    {
        return $this->applyOptions($request, $this->requestOptions);
    }

    protected function applyResponseOptions(ResponseInterface $response): ResponseInterface
    {
        return $this->applyOptions($response, $this->responseOptions);
    }

    protected function applyOptions(RequestInterface|ResponseInterface $transportObject, array $options): RequestInterface|ResponseInterface
    {
        $config = $transportObject->getConfig();
        $configOptions = $config->getOptions() ?? [];
        foreach ($configOptions as $name => $config)
        {
            $optionHandler = $options[$name];
            if ($optionHandler->supports($transportObject))
            {
                $optionHandler->configure($config);
                $transportObject = $optionHandler->process($transportObject);
                $transportObject->addOption($optionHandler::class);
            }
        }

        return $transportObject;
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

        $responseContent = new ResponseContent();
        $responseContent->setRawContent($transportResponse->getContent());

        return (new Response())
            ->setConfig($actionConfig->getResponseConfig())
            ->setStatusCode($responseStatusCode)
            ->setExceptionConfig($exception)
            ->setHeaders($transportResponse->getHeaders())
            ->setContent($responseContent);
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