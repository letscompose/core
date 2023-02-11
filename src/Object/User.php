<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Object;

use LetsCompose\Core\Interface\PropertyListInterface;
use LetsCompose\Core\Interface\UserRefInterface;
use LetsCompose\Core\Object\Tools\PropertyList;

class User implements UserRefInterface
{
    private ?string $ref = null;

    private PropertyListInterface $properties;

    public function __construct()
    {
        $this->properties = new PropertyList();
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): User
    {
        $this->ref = $ref;
        return $this;
    }

    /**
     * @return PropertyList
     */
    public function getProperties(): PropertyList
    {
        return $this->properties;
    }

    public function setProperties(PropertyList $properties): self
    {
        $this->properties = $properties;
        return $this;
    }
}