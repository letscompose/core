<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace LetsCompose\Core\HttpClient\Config\Option;

use LetsCompose\Core\HttpClient\Config\ConfigInterface;
use LetsCompose\Core\HttpClient\Option\RequestOptionInterface;
use LetsCompose\Core\HttpClient\Option\ResponseOptionInterface;

interface OptionConfigInterface extends ConfigInterface, ObjectConfigInterface
{
    const TYPE_REQUEST = 'request';
    const TYPE_RESPONSE = 'response';

    const TYPE_MAP = [
        self::TYPE_REQUEST,
        self::TYPE_RESPONSE,
    ];

    const INTERFACE_MAP = [
        self::TYPE_REQUEST => RequestOptionInterface::class,
        self::TYPE_RESPONSE => ResponseOptionInterface::class,
    ];

    public function getName(): string;
    public function setName(string $name): self;
    public function getLoaderConfig(): ?OptionLoaderConfigInterface;
    public function setLoaderConfig(?OptionLoaderConfigInterface $loaderConfig): self;
    public function hasLoaderConfig(): bool;

}