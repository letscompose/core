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

use JsonException;
use LetsCompose\Core\HttpClient\Config\Request\RequestMethodEnum;
use LetsCompose\Core\HttpClient\Request\RequestInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SymfonyHttpClientTransport implements TransportInterface
{
    public function __construct(private readonly HttpClientInterface $client)
    {
    }

    /**
     * @param RequestInterface $request
     * @return TransportResponseInterface
     * @throws JsonException
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function send(RequestInterface $request): TransportResponseInterface
    {
        $requestData = $request->getData();
        $data['headers'] = $request->getHeaders();

        switch (RequestMethodEnum::from($request->getMethod())) {
            case RequestMethodEnum::GET:
                $data['query'] = array_replace($request->getQueryParams(),$requestData);
                break;
            default:
                $dataKey = 'body';
                if (\array_search('application/json', $data['headers'])) {
                    $dataKey = 'json';
                }
                $data[$dataKey] = $requestData;
                break;
        }

        $response = $this->client->request($request->getMethod(), $request->getUri(), $data);
        $responseStatusCode = $response->getStatusCode();
        $responseHeaders = $this->getResponseHeaders($response);
        $responseData = $this->getResponseData($response);
        return new TransportResponse($responseStatusCode, $responseHeaders, $responseData);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function getResponseHeaders(ResponseInterface $response): array
    {
        return $response->getHeaders(false);
    }

    /**
     * @param ResponseInterface $response
     * @return mixed
     * @throws JsonException
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    protected function getResponseData(ResponseInterface $response): mixed
    {
        $data = $response->getContent(false);

        if (true === $this->isJsonResponseType($response)) {
            $data = json_decode(json: $data, associative: true, flags: JSON_THROW_ON_ERROR);
        }

        return $data;
    }

    /**
     * @param ResponseInterface $response
     * @return bool
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function isJsonResponseType(ResponseInterface $response): bool
    {
        $headers = $response->getHeaders(false);
        $jsonTypes = [
            'application/json',
            'application/x-javascript',
            'text/javascript',
            'text/x-javascript',
            'text/x-json',
        ];

        foreach ($headers as $name => $values)
        {
            if (strtolower($name) === 'content-type')
            {
                foreach ($values as $value)
                {
                    foreach ($jsonTypes as $type)
                    {
                        if (str_starts_with(strtolower($value), $type))
                            {
                                return true;
                            }
                     }
                }
            }
        }
        return false;
    }
}