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

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\MustImplementException;
use LetsCompose\Core\Storage\Adapter\AdapterInterface;
use LetsCompose\Core\Storage\Adapter\CompositeAdapterInterface;
use LetsCompose\Core\Storage\Config\Adapter\AdapterConfigInterface;
use LetsCompose\Core\Storage\Exception\UnknownStorageClassException;
use LetsCompose\Core\Storage\Exception\UnknownStorageResourceClassException;
use LetsCompose\Core\Storage\Resource\ResourceInterface;
use LetsCompose\Core\Tools\ExceptionHelper;
use LetsCompose\Core\Tools\ObjectHelper;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class AdapterFactory implements AdapterFactoryInterface
{
    /**
     * @throws UnknownStorageResourceClassException
     * @throws MustImplementException
     * @throws ExceptionInterface
     */
    public static function create(AdapterConfigInterface $config): AdapterInterface
    {
        $adapterClass = $config->getClass();
        if (false === class_exists($adapterClass))
        {
            ExceptionHelper::create(new UnknownStorageClassException())
                ->message('You try to create adapter from unknown adapter class [%s]', $adapterClass)
                ->throw()
            ;
        }

        if (false === ObjectHelper::hasInterface($adapterClass,AdapterInterface::class))
        {
            ExceptionHelper::create(new MustImplementException())
                ->message('Storage adapter must implement an interface [%s]', AdapterInterface::class)
                ->throw()
            ;
        }

        /**
         * @var AdapterInterface $adapter
         */
        $adapter = new $adapterClass();
        foreach ($config->getResourceConfigList() as $resourceConfig)
        {
            $resourceClass = $resourceConfig->getClass();
            if (false === class_exists($resourceClass))
            {
                ExceptionHelper::create(new UnknownStorageResourceClassException())
                    ->message('You try to add storage resource config for unknown resource class [%s]', $adapterClass)
                    ->throw()
                ;
            }

            if (false === ObjectHelper::hasInterface($resourceClass,ResourceInterface::class))
            {
                ExceptionHelper::create(new MustImplementException())
                    ->message('Storage resource must implement an interface [%s]', ResourceInterface::class)
                    ->throw()
                ;
            }

            $adapter->addResourceConfig($resourceConfig);
        }

        if ($adapter instanceof CompositeAdapterInterface)
        {
            /// build actions
        }

        return $adapter;

    }

}