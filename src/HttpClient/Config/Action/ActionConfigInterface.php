<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace LetsCompose\Core\HttpClient\Config\Action;

use LetsCompose\Core\HttpClient\Config\ConfigInterface;
use LetsCompose\Core\HttpClient\Config\Request\RequestConfigInterface;
use LetsCompose\Core\HttpClient\Config\Response\ResponseConfig;
use LetsCompose\Core\HttpClient\Config\ResponseException\ExceptionConfigList;

interface ActionConfigInterface extends ConfigInterface
{
    public function getRequestConfig(): RequestConfigInterface;
    public function getResponseConfig(): ?ResponseConfig;
    public function getResponseExceptionConfig(): ?ExceptionConfigList;
}