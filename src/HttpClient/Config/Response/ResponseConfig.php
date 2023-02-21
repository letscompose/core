<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace LetsCompose\Core\HttpClient\Config\Response;

use LetsCompose\Core\HttpClient\Config\ConfigInterface;

class ResponseConfig implements ResponseConfigInterface
{
    private string $path;

    private ?array $headers = null;

    public function getPath(): string
    {
        return $this->path;
    }
    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    public function getHeaders(): ?array
    {
        return $this->headers;
    }

    public function setHeaders(?array $headers): ResponseConfig
    {
        $this->headers = $headers;
        return $this;
    }
}