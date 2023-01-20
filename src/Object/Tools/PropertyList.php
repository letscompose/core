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
use LetsCompose\Core\Exception\NotUniqueException;
use LetsCompose\Core\Interface\PropertyInterface;
use LetsCompose\Core\Interface\PropertyListInterface;
use LetsCompose\Core\Interface\UniquePropertyInterface;
use LetsCompose\Core\Tools\ExceptionHelper;

class PropertyList implements PropertyListInterface
{
    /**
     * @var PropertyInterface[]
     */
    private array $properties = [];

    /**
     * @param string $name
     * @return PropertyInterface
     * @throws ExceptionInterface
     * @throws NotExistsException
     */
    public function getOne(string $name): PropertyInterface
    {
        $result = $this->get($name);
        if (1 < count($result))
        {
            $this->throwNotUniqueException($name);
        }
        return current($result);
    }

    /**
     * @param string $name
     * @return array|PropertyInterface[]
     * @throws ExceptionInterface
     * @throws NotExistsException
     */
    public function get(string $name): array
    {
        $result = [];
        foreach ($this->properties as $property)
        {
            if ($name === $property->getName())
            {
                $result[] = $property;
            }
        }

        if (!$result)
        {
            $this->throwNotExistsException($name);
        }

        return $result;
    }

    public function has(string $name): bool
    {
        foreach ($this->properties as $property)
        {
            if ($name === $property->getName())
            {
                return true;
            }
        }
        return false;
    }


    public function add(string $name, string $value): self
    {
        $propertyClass = Property::class;
        if (class_exists($name))
        {
            $propertyClass = $name;
        }


        $property = (new $propertyClass())
            ->setName($name)
            ->setValue($value)
        ;

        if ($property instanceof UniquePropertyInterface)
        {
            if ($this->has($name)) {
                $this->throwNotUniqueException($name);
            }
        }

        $this->properties[] = $property;

        return $this;
    }


    /**
     * @param PropertyInterface $property
     * @return bool
     */
    public function remove(PropertyInterface $property): bool
    {
        $instanceHash = spl_object_hash($property);
        $propertyIndex = null;
        foreach ($this->properties as $idx => $property)
        {
            if ($instanceHash === spl_object_hash($property))
            {
                $propertyIndex = $idx;
                break;
            }
        }

        if (null !== $propertyIndex)
        {
            unset($this->properties[$propertyIndex]);
            return true;
        }

        return false;
    }

    public function clear(): self
    {
        $this->properties = [];
        return $this;
    }


    /**
     * @throws ExceptionInterface
     * @throws NotExistsException
     */
    private function throwNotExistsException(string $name)
    {
            ExceptionHelper::create(new NotExistsException())
                ->message('You try to get not existing property [%s]', $name)
                ->throw()
            ;
    }

    /**
     * @param string $name
     * @return void
     * @throws ExceptionInterface
     * @throws NotUniqueException
     */
    private function throwNotUniqueException(string $name): void
    {
        ExceptionHelper::create(new NotUniqueException())
            ->message('Not Unique property [%s]', $name)
            ->throw()
        ;
    }

}