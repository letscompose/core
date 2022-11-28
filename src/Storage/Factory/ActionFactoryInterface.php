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

use LetsCompose\Core\Storage\Actions\ActionInterface;
use LetsCompose\Core\Storage\Config\ActionConfigInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
interface ActionFactoryInterface
{
    public static function create(ActionConfigInterface $config): ActionInterface;
}