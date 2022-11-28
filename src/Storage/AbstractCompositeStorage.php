<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Storage\Actions\ActionHandlerInterface;
use LetsCompose\Core\Storage\Config\ActionsConfigListInterface;
use LetsCompose\Core\Storage\Exception\ActionNotFoundException;
use LetsCompose\Core\Storage\Exception\ActionNotImplementedException;
use LetsCompose\Core\Storage\Resource\ResourceInterface;
use LetsCompose\Core\Tools\ExceptionHelper;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
abstract class AbstractCompositeStorage extends AbstractResourceStorage
{
    /**
     * @var ActionsConfigListInterface[]
     */
    protected array $actionConfigList = [];

    protected ActionHandlerInterface $actionHandler;

    /**
     * @return ActionsConfigListInterface[]
     */
    public function getActionConfigList(): array
    {
        return $this->actionConfigList;
    }

    /**
     * @param ActionsConfigListInterface[] $actionConfigList
     */
    public function setActionConfigList(array $actionConfigList): AbstractCompositeStorage
    {
        $this->actionConfigList = $actionConfigList;
        return $this;
    }

    /**
     * @return ActionHandlerInterface
     */
    public function getActionHandler(): ActionHandlerInterface
    {
        return $this->actionHandler;
    }

    /**
     * @param ActionHandlerInterface $actionHandler
     * @return AbstractCompositeStorage
     */
    public function setActionHandler(ActionHandlerInterface $actionHandler): AbstractCompositeStorage
    {
        $this->actionHandler = $actionHandler;
        return $this;
    }

    /**
     * @throws ActionNotImplementedException
     * @throws ActionNotFoundException
     * @throws ExceptionInterface
     */
    public function execute(string $actionName, ResourceInterface $resource, ...$params)
    {
        $actionList = $this->actionConfigList[$resource::class];
        if (false === $actionList->hasAction($actionName))
        {
            ExceptionHelper::create(new ActionNotImplementedException())
                ->message('Not implemented storage action [%s]', $actionName)
                ->throw();
        }

        $actionConfig = $actionList->getAction($actionName);
        $action = $this->actionHandler->create($actionConfig);

        return $action->execute($resource, ...$params);
    }
}