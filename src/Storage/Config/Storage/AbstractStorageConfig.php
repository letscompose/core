<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\Config\Storage;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Storage\Config\AbstractConfig;
use LetsCompose\Core\Storage\Config\Adapter\AdapterConfigInterface;
use LetsCompose\Core\Storage\Exception\ConfigAlreadyDefinedException;
use LetsCompose\Core\Tools\ExceptionHelper;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
abstract class AbstractStorageConfig extends AbstractConfig implements StorageConfigInterface
{
    protected string $rootPath;
    /**
     * @var AdapterConfigInterface[]
     */
    protected array $adapterConfigList = [];

    public function setRootPath(string $rootPath): AbstractStorageConfig
    {
        $this->rootPath = $rootPath;
        return $this;
    }

    public function getRootPath(): string
    {
        return $this->rootPath;
    }

    /**
     * @inheritDoc
     * @throws ConfigAlreadyDefinedException
     * @throws ExceptionInterface
     */
    public function setAdapterConfigList(array $configList): self
    {
        $this->adapterConfigList = [];
        foreach ($configList as $adapterConfig)
        {
            $this->addAdapterConfig($adapterConfig);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAdapterConfigList(): array
    {
        return $this->adapterConfigList;
    }

    /**
     * @throws ConfigAlreadyDefinedException
     * @throws ExceptionInterface
     */
    public function addAdapterConfig(AdapterConfigInterface $config): self
    {
        if (true === $this->hasAdapterConfig($config))
        {
            ExceptionHelper::create(new ConfigAlreadyDefinedException())
                ->message('Already defined resource config [%s]', $config->getClass())
                ->throw();
        }
        $this->adapterConfigList[$config->getClass()] = $config;
        return $this;
    }

    public function hasAdapterConfig(AdapterConfigInterface $config): bool
    {
        return \array_key_exists($config->getClass(), $this->adapterConfigList);
    }
}