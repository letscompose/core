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

interface ResponseConfigInterface extends ConfigInterface
{
    public function getPath(): string;
    public function setPath(string $path): self;
    public function getHeaders(): ?array;
    public function setHeaders(?array $headers): self;
    public function getOptions(): ?array;
    public function setOptions(?array $options): self;
}