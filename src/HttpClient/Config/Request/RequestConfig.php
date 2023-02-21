<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace LetsCompose\Core\HttpClient\Config\Request;

class RequestConfig implements RequestConfigInterface
{
    const CONFIG_KEY_METHOD = 'method';
    const CONFIG_KEY_URI = 'uri';
    const CONFIG_KEY_HEADERS = 'headers';
    const CONFIG_KEY_QUERY_PARAMS = 'query_params';

    const CONFIG_REQUIRED_KEYS = [
        self::CONFIG_KEY_METHOD,
        self::CONFIG_KEY_URI,
    ];

    const CONFIG_OPTIONAL_KEYS = [
        self::CONFIG_KEY_USE_DEFAULTS,
        self::CONFIG_KEY_HEADERS,
        self::CONFIG_KEY_QUERY_PARAMS,
    ];

    private string $path;

    private string $method;

    private ?string $uriPrefix = null;

    private string $uri;

    private ?array $headers = null;
    private ?array $queryParams = null;

    private bool $useDefaults = true;

    public function getPath(): string
    {
        return $this->path;
    }
    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;
        return $this;
    }

    public function getUriPrefix(): ?string
    {
        return $this->uriPrefix;
    }

    public function setUriPrefix(?string $uriPrefix): RequestConfig
    {
        $this->uriPrefix = $uriPrefix;
        return $this;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function setUri(string $uri): self
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getHeaders(): ?array
    {
        return $this->headers;
    }

    /**
     * @param array|null $headers
     * @return RequestConfig
     */
    public function setHeaders(?array $headers): RequestConfig
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getQueryParams(): ?array
    {
        return $this->queryParams;
    }

    /**
     * @param array|null $queryParams
     * @return RequestConfig
     */
    public function setQueryParams(?array $queryParams): RequestConfig
    {
        $this->queryParams = $queryParams;
        return $this;
    }

    /**
     * @return bool
     */
    public function isUseDefaults(): bool
    {
        return $this->useDefaults;
    }

    /**
     * @param bool $useDefaults
     * @return RequestConfig
     */
    public function setUseDefaults(bool $useDefaults): self
    {
        $this->useDefaults = $useDefaults;
        return $this;
    }
}