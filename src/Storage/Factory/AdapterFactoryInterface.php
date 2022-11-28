<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\Factory;

use LetsCompose\Core\Storage\Adapter\AdapterInterface;
use LetsCompose\Core\Storage\Config\Adapter\AdapterConfigInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
interface AdapterFactoryInterface
{
    public static function create(AdapterConfigInterface $config): AdapterInterface;
}