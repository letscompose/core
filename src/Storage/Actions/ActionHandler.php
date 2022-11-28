<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\Actions;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\MustImplementException;
use LetsCompose\Core\Storage\Config\ActionConfigInterface;
use LetsCompose\Core\Storage\Exception\ActionNotFoundException;
use LetsCompose\Core\Storage\Factory\ActionFactory;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class ActionHandler implements ActionHandlerInterface
{
    private \SplObjectStorage $actions;

    public function __construct()
    {
        $this->actions = new \SplObjectStorage();
    }

    /**
     * @throws MustImplementException
     * @throws ActionNotFoundException
     * @throws ExceptionInterface
     */
    public function create(ActionConfigInterface $actionConfig): ActionInterface
    {
        if (false === $this->actions->contains($actionConfig))
        {
            $action = ActionFactory::create($actionConfig);
            $this->actions->attach($actionConfig, $action);
        } else
        {
            $action = $this->actions[$actionConfig];
        }
        return $action;
    }
}