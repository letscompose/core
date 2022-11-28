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

use LetsCompose\Core\Storage\Exception\ActionConfigAlreadyDefinedException;
use LetsCompose\Core\Storage\Exception\ActionConfigNotFoundException;
use LetsCompose\Core\Tools\ExceptionHelper;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class ActionsConfigList implements ActionsConfigListInterface
{

    /**
     * @param array $actions
     */
    public function __construct(
        protected array $actions = []
    )
    {
    }

    public function addAction(ActionConfigInterface $action): ActionsConfigListInterface
    {
        $actionName = $action->getName();
        if (false !== $this->hasAction($actionName)) {
            ExceptionHelper::create(new ActionConfigAlreadyDefinedException())
                ->message('action config [%s] already exist in actions list', $actionName)
                ->throw();
        }
        $this->actions[$actionName] = $action;
        return $this;
    }

    public function getAction(string $name): ActionConfigInterface
    {
        if (false === $this->hasAction($name)) {
            ExceptionHelper::create(new ActionConfigNotFoundException())
                ->message('action config [%s] does not exist in actions list', $name)
                ->throw();
        }
        return $this->actions[$name];
    }

    public function hasAction(string $name): bool
    {
        return array_key_exists($name, $this->actions);
    }

    public function hasActions(): bool
    {
        return 0 < count($this->actions);
    }

}