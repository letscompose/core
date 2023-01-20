<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Object\Tools;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\NotExistsException;
use LetsCompose\Core\Interface\PropertyInterface;
use LetsCompose\Core\Interface\UniquePropertyListInterface;
use LetsCompose\Core\Tools\ExceptionHelper;

class Property implements PropertyInterface
{
    private string $name;

    private mixed $value;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue(mixed $value): self
    {
        $this->value = $value;
        return $this;
    }


}