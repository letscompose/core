<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\Adapter;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\MustImplementException;
use LetsCompose\Core\Storage\Actions\ActionInterface;
use LetsCompose\Core\Storage\StorageInterface;
use LetsCompose\Core\Tools\ExceptionHelper;
use LetsCompose\Core\Tools\ObjectHelper;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
abstract class AbstractCompositeAdapter extends AbstractAdapter implements CompositeAdapterInterface
{
    /**
     * @var string[]
     */
    protected array $actions = [];

    /**
     * @var ActionInterface[]
     */
    private array $actionInstanceList = [];

    /**
     * @throws MustImplementException
     * @throws ExceptionInterface
     */
    public function __construct(StorageInterface $storage)
    {
        $actions = $this->getActionsClassList();
        $this->registerActions($actions);

        parent::__construct($storage);
    }


    /**
     * @param array<static> $actionsClassList
     * @return $this
     * @throws ExceptionInterface
     * @throws MustImplementException
     */
    public function registerActions(array $actionsClassList): self
    {
        foreach ($actionsClassList as $actionsClass)
        {
            if (false === ObjectHelper::hasInterface($actionsClass, ActionInterface::class))
            {
                ExceptionHelper::create(new MustImplementException())
                    ->message('Action [%s] must implement an interface [%s]', $actionsClass, ActionInterface::class)
                    ->throw()
                    ;
            }
            /**
             *  @var ActionInterface $actionsClass
             */
            $this->actions[($actionsClass)::storageMethod()] = $actionsClass;
        }
        return $this;
    }

    public function hasAction(string $actionName): bool
    {
        return \array_key_exists($actionName, $this->actions);
    }

    public function getAction(string $actionName): ActionInterface
    {
        if (true === \array_key_exists($actionName, $this->actionInstanceList))
        {
            return $this->actionInstanceList[$actionName];
        }

        $actionClass = $this->actions[$actionName];
        $action = new $actionClass();
        $action->setStorage($this->getStorage());
        $this->actionInstanceList[$actionName] = $action;
        return $action;
    }

    public function execute(string $action, ...$params)
    {
        if ($this->hasAction($action))
        {
            $action = $this->getAction($action);
            return $action->execute(...$params);
        }
        return parent::execute($action, ...$params); // TODO: Change the autogenerated stub
    }
}