<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace LetsCompose\Core\HttpClient\Config\ResponseException;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\InvalidLogicException;
use LetsCompose\Core\Exception\NotUniqueException;

class ExceptionConfigList implements ExceptionConfigListInterface
{
    /**
     * @var ?ExceptionConfigInterface[]
     */
    private array $exceptionConfigs = [];

    public function getExceptionConfigs(): array
    {
        return $this->exceptionConfigs;
    }

    /**
     * @throws ExceptionInterface
     */
    public function addExceptionConfig(ExceptionConfigInterface $exceptionConfig): self
    {

        if ($this->hasExceptionConfig($exceptionConfig->getClass()))
        {
            throw (new NotUniqueException())
                ->setMessage('You try add already configured exception [%s]', $exceptionConfig->getClass())
            ;
        }

        $configuredCodes = $exceptionConfig->getWhenResponseCode();
        if (!empty($configuredCodes) && $exceptionConfig->isDefault())
        {
            throw  (new InvalidLogicException())
            ->setMessage(
                'You try to configure [%s] exception as default with not empty [%s] key, please fix your config',
                $exceptionConfig->getClass(),
                ExceptionConfigInterface::CONFIG_KEY_WHEN_RESPONSE_CODE
            );
        }

        if (empty($configuredCodes) || $exceptionConfig->isDefault())
        {
            $exceptionConfig->setDefault(true);
            return $this->addDefaultExceptionConfig($exceptionConfig);
        }


        if (false !== $configured = $this->getExceptionConfigByWhenResponseCode($exceptionConfig->getWhenResponseCode()))
        {

            $code = 0;
            foreach ($configured->getWhenResponseCode() as $code)
            {
                if (in_array($code, $configuredCodes))
                {
                    break;
                }
            }

            throw (new NotUniqueException())
                ->setMessage(
                    'You try configure an exception for code [%s] but this code already supported by [%s]',
                    $code,
                    $configured->getClass()
                )
            ;
        }

        $this->exceptionConfigs[$exceptionConfig->getClass()] = $exceptionConfig;
        return $this;
    }

    /**
     * @throws ExceptionInterface
     */
    protected function addDefaultExceptionConfig(ExceptionConfigInterface $exceptionConfig): self
    {
        if (false !== $configured = $this->getDefaultExceptionConfig())
        {
            throw (new NotUniqueException())
                ->setMessage(
                    'You try to overwrite already defined default exception [%s] by [%s] to resolve this issue resolve conflict in your config or add [%s] condition key',
                    $configured->getClass(),
                    $exceptionConfig->getClass(),
                    ExceptionConfigInterface::CONFIG_KEY_WHEN_RESPONSE_CODE
                )
            ;
        }

        $this->exceptionConfigs[$exceptionConfig->getClass()] = $exceptionConfig;

        return $this;
    }

    public function getDefaultExceptionConfig(): ExceptionConfigInterface|false
    {
        foreach ($this->exceptionConfigs as $config)
        {
            if (!$config->getWhenResponseCode() && $config->isDefault())
            {
                return $config;
            }
        }
        return false;
    }

    public function getExceptionConfigByWhenResponseCode(array $codes): ExceptionConfigInterface|false
    {
        foreach ($this->exceptionConfigs as $config)
        {
            if (array_intersect($config->getWhenResponseCode(), $codes))
            {
                return $config;
            }
        }
        return false;
    }

    public function hasExceptionConfig(string $class): bool
    {
        return false !== ($this->exceptionConfigs[$class] ?? false);
    }

}