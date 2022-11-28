<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\Config\Resource;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\InvalidArgumentException;
use LetsCompose\Core\Storage\Config\AbstractConfig;
use LetsCompose\Core\Tools\ExceptionHelper;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class ResourceConfig extends AbstractConfig implements ResourceConfigInterface
{
    public function __construct
    (
        protected string  $class,
        protected ?string $actionNameSpace = null,
        protected ?string $storageClass = null
    )
    {
    }

    public function setActionNameSpace(?string $actionNameSpace): ResourceConfigInterface
    {
        $this->actionNameSpace = $actionNameSpace;
        return $this;
    }

    public function getActionNameSpace(): ?string
    {
        return $this->actionNameSpace;
    }

    public function setStorageClass(string $class): ResourceConfigInterface
    {
        $this->storageClass = $class;
        return $this;
    }

    public function getStorageClass(): string
    {
        return $this->storageClass;
    }


}