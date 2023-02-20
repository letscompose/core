<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\HttpClient\Request;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\InvalidArgumentException;
use LetsCompose\Core\HttpClient\Config\Request\RequestConfigInterface;
use LetsCompose\Core\Tools\Storage\PathHelper;
use LetsCompose\Core\Tools\StringPlaceholderHelper;

class Request implements RequestInterface
{
    private string $uuid;

    private array $placeholders = [];

    private array $queryParams = [];

    private array $headers = [];

    private array $data;

    public function __construct(private readonly RequestConfigInterface $config)
    {
        $this->uuid = StringHelper::uuidv4();
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return string
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     */
    public function getUri(): string
    {
        $uri = $this->config->getUri();

        $prefix = $this->config->getUriPrefix();

        if ($prefix) {
            $uri = sprintf('%s/%s', $prefix, $uri);
        }

        if ($this->getPlaceholders())
        {
            $uri = StringPlaceholderHelper::fillPlaceholders($uri, $this->getPlaceHolders());
        }

        $uri = PathHelper::normalize($uri);
        if (false === str_starts_with($uri, '/'))
        {
            $uri .= '/';
        }

        if (!empty($queryParams = $this->getQueryParams())) {
            $uri = sprintf('%s?%s', $uri, \http_build_query($queryParams));
        }

        return $uri;
    }

    /**
     * @return array
     */
    public function getPlaceholders(): array
    {
        return $this->placeholders;
    }

    /**
     * @param array $placeholders
     * @return Request
     */
    public function setPlaceholders(array $placeholders): Request
    {
        $this->placeholders = $placeholders;
        return $this;
    }

    /**
     * @return array
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * @param array $queryParams
     * @return Request
     */
    public function setQueryParams(array $queryParams): Request
    {
        $this->queryParams = $queryParams;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     * @return Request
     */
    public function setHeaders(array $headers): Request
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return Request
     */
    public function setData(array $data): Request
    {
        $this->data = $data;
        return $this;
    }



}