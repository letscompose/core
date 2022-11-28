<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\Config\Adapter;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Storage\Config\AbstractConfig;
use LetsCompose\Core\Storage\Config\Resource\ResourceConfigInterface;
use LetsCompose\Core\Storage\Exception\ConfigAlreadyDefinedException;
use LetsCompose\Core\Tools\ExceptionHelper;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
abstract class AbstractAdapterConfig extends AbstractConfig implements AdapterConfigInterface
{
    /**
     * @var ResourceConfigInterface[]
     */
    protected array $resourceConfigList = [];

    /**
     * @inheritDoc
     * @throws ConfigAlreadyDefinedException
     * @throws ExceptionInterface
     */
    public function setResourceConfigList(array $configList): self
    {
        $this->resourceConfigList = [];
        foreach ($configList as $resourceConfig)
        {
            $this->addResourceConfig($resourceConfig);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getResourceConfigList(): array
    {
        return $this->resourceConfigList;
    }

    /**
     * @throws ConfigAlreadyDefinedException
     * @throws ExceptionInterface
     */
    public function addResourceConfig(ResourceConfigInterface $config): self
    {
        if (true === $this->hasResourceConfig($config))
        {
            ExceptionHelper::create(new ConfigAlreadyDefinedException())
                ->message('Already defined resource config [%s]', $config->getClass())
                ->throw();
        }
        $this->resourceConfigList[$config->getClass()] = $config;
        return $this;
    }

    public function hasResourceConfig(ResourceConfigInterface $config): bool
    {
        return \array_key_exists($config->getClass(), $this->resourceConfigList);
    }
}