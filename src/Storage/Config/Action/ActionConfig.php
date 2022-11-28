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

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class ActionConfig extends AbstractConfig implements ActionConfigInterface
{
    public function __construct(
        private string $name,
        private string $nameSpace,
        protected string $class,
        private string $storageClass,
        private string $resourceClass,
    )
    {

    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getNameSpace(): string
    {
        return $this->nameSpace;
    }

    public function setNameSpace(string $nameSpace): ActionConfig
    {
        $this->nameSpace = $nameSpace;
        return $this;
    }

    public function getStorageClass(): string
    {
        return $this->storageClass;
    }

    public function setStorageClass(string $storageClass): ActionConfig
    {
        $this->storageClass = $storageClass;
        return $this;
    }

    public function getResourceClass(): string
    {
        return $this->resourceClass;
    }

    public function setResourceClass(string $resourceClass): ActionConfig
    {
        $this->resourceClass = $resourceClass;
        return $this;
    }
}
