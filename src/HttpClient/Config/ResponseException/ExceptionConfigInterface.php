<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace LetsCompose\Core\HttpClient\Config\ResponseException;

use LetsCompose\Core\HttpClient\Config\ConfigInterface;

interface ExceptionConfigInterface extends ConfigInterface
{
    const CONFIG_KEY_RAISE_WHEN_RESPONSE_CODE = 'raise_when_response_code';
}