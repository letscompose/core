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

use LetsCompose\Core\HttpClient\Config\ClientConfig;
use LetsCompose\Core\HttpClient\Config\ClientConfigInterface;

interface HttpClientInterface
{
    public function loadConfig(string $configFile): self;
}