<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\HttpClient\Option;

class MapKeysOptionLoader implements OptionLoaderInterface
{
    public function load(string $class, mixed $config): OptionInterface
    {
        return new $class($config);
    }

}