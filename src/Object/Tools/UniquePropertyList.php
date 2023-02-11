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
use LetsCompose\Core\Interface\UniquePropertyListInterface;
use LetsCompose\Core\Tools\ExceptionHelper;
use LetsCompose\Core\Tools\ObjectHelper;

class UniquePropertyList extends PropertyList implements UniquePropertyListInterface
{
    protected const DEFAULT_PROPERTY_CLASS = UniqueProperty::class;

    /**
     * @throws ExceptionInterface
     */
    protected function checkPropertyInstance(PropertyInterface $property): PropertyInterface
    {
        if (false === $property instanceof UniquePropertyInterface)
        {
            ExceptionHelper::create(new MustImplementException())
                ->message('Property [%s] of [%s] must implement interface [%s]', $property::class, ObjectHelper::getClassShortName(get_class($this)), UniquePropertyInterface::class)
                ->throw();
        }
        return parent::checkPropertyInstance($property);
    }
}