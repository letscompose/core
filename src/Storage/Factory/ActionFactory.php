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
use LetsCompose\Core\Storage\Actions\ActionInterface;
use LetsCompose\Core\Storage\Config\ActionConfigInterface;
use LetsCompose\Core\Storage\Exception\ActionNotFoundException;
use LetsCompose\Core\Tools\ExceptionHelper;
use LetsCompose\Core\Tools\ObjectHelper;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class ActionFactory implements ActionFactoryInterface
{
    /**
     * @throws ActionNotFoundException
     * @throws MustImplementException
     * @throws ExceptionInterface
     */
    public static function create(ActionConfigInterface $config): ActionInterface
    {
        $actionClass = $config->getClass();
        if (false === class_exists($actionClass))
        {
            ExceptionHelper::create(new ActionNotFoundException())
                ->message('Not found storage action [%s]', $actionClass)
                ->throw();
        }

        if (false === ObjectHelper::hasInterface($actionClass, ActionInterface::class))
        {
            ExceptionHelper::create(new MustImplementException())
                ->message('Action class [%s] must implement an interface [%s]', $actionClass, ActionInterface::class)
                ->throw();
        }
        return new $actionClass;
    }
}