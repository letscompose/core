<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\Config;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\InvalidArgumentException;
use LetsCompose\Core\Tools\ExceptionHelper;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
abstract class AbstractConfig implements ConfigInterface
{
    protected string $class = '';

    public function setClass(string $class): self
    {
        $this->class = $class;
        return $this;
    }

    public function getClass(): string
    {
        return $this->class;
    }
}