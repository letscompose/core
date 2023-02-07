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
use LetsCompose\Core\Exception\MustImplementException;
use LetsCompose\Core\Exception\NotExistsException;
use LetsCompose\Core\Exception\NotUniqueException;
use LetsCompose\Core\Interface\PropertyInterface;
use LetsCompose\Core\Interface\PropertyListInterface;
use LetsCompose\Core\Interface\UniquePropertyInterface;
use LetsCompose\Core\Tools\ExceptionHelper;
use LetsCompose\Core\Tools\ObjectHelper;
use ReturnTypeWillChange;

class PropertyList implements PropertyListInterface, \Iterator, \Countable
{
    /**
     * @var PropertyInterface[]
     */
    protected array $properties = [];

    protected const DEFAULT_PROPERTY_CLASS = Property::class;

    protected int $position;

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

    /**
     * @throws ExceptionInterface
     * @throws MustImplementException
     */
    protected function createPropertyInstance(string|PropertyInterface $class): PropertyInterface
    {
        if ($class instanceof PropertyInterface)
        {
            return $class;
        }

        $propertyClass = static::DEFAULT_PROPERTY_CLASS;
        if (class_exists($class)) {
            $propertyClass = $class;
        }

        if (false === ObjectHelper::hasInterface(PropertyInterface::class, $propertyClass))
        {
            ExceptionHelper::create(new MustImplementException())
                ->message('Property [%s] of [%s] must implement interface [%s]', $propertyClass, ObjectHelper::getClassShortName(get_class($this)), PropertyInterface::class)
                ->throw();
        }

        return new $propertyClass();
    }

    protected function checkPropertyInstance(PropertyInterface $property): PropertyInterface
    {
        return $property;
    }


    /**
     * @throws NotUniqueException
     * @throws MustImplementException
     * @throws ExceptionInterface
     */
    public function add(string $name, mixed $value): self
    {
        $property = $this->createPropertyInstance($name);
        $property = $this->checkPropertyInstance($property);


        if ($property instanceof UniquePropertyInterface)
        {
            if ($this->has($name)) {
                $this->throwNotUniqueException($name);
            }
        }

        $property
            ->setName($name)
            ->setValue($value)
        ;

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

    public static function createFromArray(array $properties): PropertyListInterface
    {
        $propertyListClass = get_called_class();
        /**
         * @var PropertyListInterface $propertyList
         */
        $propertyList = new $propertyListClass();
        foreach ($properties as $name => $value) {
            $propertyList->add($name, $value);
        }
        return $propertyList;
    }

    // implement \Iterator
    public function rewind(): void {
        $this->position = 0;
    }

    #[ReturnTypeWillChange]
    public function current() {
        return $this->properties[$this->position];
    }

    public function key(): int {
        return $this->position;
    }

    public function next(): void {
        ++$this->position;
    }

    public function valid(): bool {
        return array_key_exists($this->position, $this->properties);
    }

    // implement \Countable
    public function count(): int
    {
        return count($this->properties);
    }
}