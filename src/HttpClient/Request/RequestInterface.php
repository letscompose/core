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

use LetsCompose\Core\HttpClient\Config\Request\RequestConfigInterface;

interface RequestInterface
{
    public function getConfig(): RequestConfigInterface;
    public function getUuid(): string;

    public function getPath(): string;

    public function getMethod(): string;

    public function getUri(): string;

    public function getPlaceholders(): array;

    public function setPlaceholders(array $placeholders): self;

    public function getQueryParams(): array;

    public function setQueryParams(array $queryParams): self;

    public function addQueryParams($queryParams): self;

    public function getHeaders(): array;

    public function setHeaders(array $headers): self;

    public function addHeaders(array $headers): self;

    public function getData(): array;

    public function setData(array $data): self;

    public function addOption(string $optionClass): self;
    public function hasOption(string $optionClass): bool;
}