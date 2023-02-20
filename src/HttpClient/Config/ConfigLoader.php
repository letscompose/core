<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace LetsCompose\Core\HttpClient\Config;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\InvalidArgumentException;
use LetsCompose\Core\Exception\InvalidLogicException;
use LetsCompose\Core\Exception\MustImplementException;
use LetsCompose\Core\Exception\NotExistsException;
use LetsCompose\Core\HttpClient\Config\Action\ActionConfig;
use LetsCompose\Core\HttpClient\Config\Action\ActionConfigInterface;
use LetsCompose\Core\HttpClient\Config\Option\OptionConfig;
use LetsCompose\Core\HttpClient\Config\Option\OptionLoaderConfig;
use LetsCompose\Core\HttpClient\Config\Response\ResponseConfigInterface;
use LetsCompose\Core\HttpClient\Config\ResponseException\ExceptionConfig;
use LetsCompose\Core\HttpClient\Config\ResponseException\ExceptionConfigList;
use LetsCompose\Core\HttpClient\Config\Request\RequestConfig;
use LetsCompose\Core\HttpClient\Config\Request\RequestConfigInterface;
use LetsCompose\Core\HttpClient\Config\Response\ResponseConfig;
use LetsCompose\Core\HttpClient\Option\OptionInterface;
use LetsCompose\Core\HttpClient\Option\OptionLoaderInterface;
use LetsCompose\Core\Tools\ArrayHelper;
use LetsCompose\Core\Tools\Data\Hydrator;
use LetsCompose\Core\Tools\ObjectHelper;

class ConfigLoader implements ConfigLoaderInterface
{
    protected const CONFIG_KEY = 'http_client';
    protected const CONFIG_KEY_DEFAULT_REQUEST_OPTIONS = 'default_request_config';
    protected const CONFIG_KEY_DEFAULT_RESPONSE_OPTIONS = 'default_response_config';
    protected const CONFIG_KEY_DEFAULT_RESPONSE_EXCEPTION_CONFIG = 'default_response_exception_config';
    protected const CONFIG_KEY_OPTIONS_EXTEND = 'options-extend';
    protected const CONFIG_KEY_ACTIONS = 'actions';
    protected const CONFIG_KEY_ACTION_REQUEST = 'request';
    protected const CONFIG_KEY_ACTION_RESPONSE = 'response';
    protected const CONFIG_KEY_ACTION_RESPONSE_EXCEPTION = 'response_exception';

    /**
     * @throws ExceptionInterface
     */
    public function load(array $config): ConfigInterface
    {
        $clientConfig = new ClientConfig();

        $config = $this->getConfig($config, static::CONFIG_KEY);
        $this->validate($config);

        $options = $this->extendOptions($config[static::CONFIG_KEY_OPTIONS_EXTEND] ?? []);
        $actions = $this->loadActions($config);

        $clientConfig->setOptions($options);
        $clientConfig->setActions($actions);

        return $clientConfig;

    }

    protected function extendOptions(array $config)
    {
        $getOptionsConfig = function (array $optionsConfig, array &$path = []) use (&$getOptionsConfig): array
        {
            $defaults  = [
                'class' => null,
                'name' => null,
                'config' => [],
                'priority' => null,
            ];

            $configList = [];

            foreach ($optionsConfig as $option => $config)
            {
                if (is_string($config))
                {
                    $config = ['class' => $config] ;
                }
                $hasConfig = array_intersect_key($config, $defaults);
                if (!$hasConfig)
                {
                    $path[] = $option;
                    $configList = array_merge($configList, $getOptionsConfig($config, $path));
                    $path = [];
                } else {
                    $key = $path[0] ?? $option;
                    $config['name'] = $key;
                    $config['class'] = $config['class'] ?? $option;
                    $config = array_replace($defaults, $config);
                    if (false === empty($config['loader'] ?? [] ))
                    {
                        $config  = array_replace($config, $getOptionsConfig(['loader' => $config['loader']]));
                    }
                    $configList[$key][] = $config;
                }

            }

            return $configList;
        };


        $validateObjectConfig = function (string $option, string $interface, array $config): array
        {
            if (empty($config))
            {
                throw (new InvalidArgumentException())
                    ->setMessage('empty [%s] HttpClient option config', $option)
                ;
            }

            if (1 < count($config))
            {
                throw (new InvalidArgumentException())
                    ->setMessage('HttpClient option [%s] can be defined only by one config', $option)
                ;
            }

            $config = current($config);
            $class = $config['class'] ?? '';
            if (empty($class))
            {
                throw (new InvalidArgumentException())
                    ->setMessage('HttpClient option [%s] config must define an valid [class] key', $option)
                ;
            }

            if (false === class_exists($class))
            {
                throw (new NotExistsException())
                    ->setMessage(
                        'HttpClient option [%s] class [%s] doest not exist',
                        $option,
                        $class
                    )
                ;
            }

            if (false === ObjectHelper::hasInterface($class, $interface))
            {
                throw (new MustImplementException())
                    ->setMessage(
                        'HttpClient option [%s] class [%s] must implement interface [%s]',
                        $option,
                        $class,
                        $interface
                    )
                ;
            }

            return $config;
        };


        /**
         * @throws InvalidArgumentException
         * @throws ExceptionInterface
         */
        $processOptionsConfig = function (array $optionsConfig) use ($validateObjectConfig): array
        {
            $result = [];
            foreach ($optionsConfig as $option => $config)
            {
                $config = $validateObjectConfig($option, OptionInterface::class, $config);
                $option = $this->createConfigObject(OptionConfig::class, $config);
                $config = $config['loader'] ?? [];
                if (!empty($config))
                {
                    $config = $validateObjectConfig($option->getName().'/loader', OptionLoaderInterface::class, $config);
                    $loader = $this->createConfigObject(OptionLoaderConfig::class, $config);
                    $option->setLoaderConfig($loader);
                }
                $result[$option->getName()] = $option;
            }
            return $result;
        };

        $result = $getOptionsConfig($config);
        if ($result)
        {
            $result = $processOptionsConfig($result);
        }
        return $result;
    }

    /**
     * @throws ExceptionInterface
     */
    protected function validate(array $config): void
    {
        $this->validateExceptionConfig(static::CONFIG_KEY_DEFAULT_RESPONSE_EXCEPTION_CONFIG, $config[static::CONFIG_KEY_DEFAULT_RESPONSE_EXCEPTION_CONFIG] ?? []);
    }

    /**
     * @return ActionConfigInterface[]
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     */
    protected function loadActions(array $clientConfig): array
    {
        $config = $this->getConfig($clientConfig, static::CONFIG_KEY_ACTIONS);
        $actions = $this->getActionsList($config);
        $actionObjects = [];

        foreach ($actions as $path => $action)
        {
            $requestConfig = $this->createRequestConfig
            (
                $path,
                $action[static::CONFIG_KEY_ACTION_REQUEST],
                $clientConfig[static::CONFIG_KEY_DEFAULT_REQUEST_OPTIONS] ?? []
            );

            $responseConfig = $this->createResponseConfig
            (
                $path,
                $action[static::CONFIG_KEY_ACTION_RESPONSE] ?? [],
                $clientConfig[static::CONFIG_KEY_DEFAULT_RESPONSE_OPTIONS] ?? []
            );

            $responseExceptionConfig = $this->createResponseExceptionConfig
            (
                $path,
                $action[static::CONFIG_KEY_ACTION_RESPONSE_EXCEPTION] ?? [],
                $clientConfig[static::CONFIG_KEY_DEFAULT_RESPONSE_EXCEPTION_CONFIG] ?? []
            );

            $actionObjects[$path] = (new ActionConfig())
                ->setPath($path)
                ->setRequestConfig($requestConfig)
                ->setResponseConfig($responseConfig)
                ->setResponseExceptionConfig($responseExceptionConfig)
            ;
        }

        return $actionObjects;
    }

    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    protected function createResponseExceptionConfig(string $path, array $config, array $defaultConfig): ExceptionConfigList
    {

        $this->validateExceptionConfig($path, $config);

        if (!empty($defaultConfig))
        {
            if ($config[ConfigInterface::CONFIG_KEY_USE_DEFAULTS] ?? true)
            {
                $config = array_replace_recursive($defaultConfig, $config);
            }
        }

        $exceptionConfigList = new ExceptionConfigList();
        $configList = $config['exceptions'] ?? [];
        foreach ($configList as $key => $exceptionConfig)
        {
            $exceptionConfig['class'] = $exceptionConfig['class'] ?? $key;
            $exceptionConfig['message_prefix'] = $config['message_prefix'];

            /**
             * @var ExceptionConfig $exceptionConfig
             */
            $exceptionConfig = $this->createConfigObject(ExceptionConfig::class, $exceptionConfig);
            try {
                $exceptionConfigList->addExceptionConfig($exceptionConfig);
            } catch (\Exception $e)
            {
                throw (new InvalidLogicException())
                    ->setMessage('Invalid response exception config section [%s]. %s', $path, $e->getMessage())
                ;
            }
        }

        return $exceptionConfigList;
    }


    /**
     * @throws InvalidArgumentException
     * @throws NotExistsException
     * @throws ExceptionInterface
     */
    protected function validateExceptionConfig(string $path, array $config): void
    {
        $configList = $config['exceptions'] ?? [];
        foreach ($configList as $key => $exceptionConfig)
        {
            $class = $exceptionConfig['class'] ?? $key;
            if (empty($class))
            {
                throw (new InvalidArgumentException())
                    ->setMessage(
                        'Invalid response exception config section [%s]. Exception config must define an valid exception class by [class] key or key of configuration block',
                        $path
                    )
                ;
            }

            if (false === class_exists($class))
            {
                throw (new NotExistsException())
                    ->setMessage(
                        'Invalid response exception config section [%s]. Class [%s] doest not exists',
                        $path,
                        $class
                    )
                ;
            }

            if (false === ObjectHelper::hasParent($class, \Exception::class))
            {
                throw (new InvalidArgumentException())
                    ->setMessage(
                        'Invalid response exception config section [%s]. Your exception class [%s] must extends [%s]',
                        $path,
                        $class,
                        \Exception::class
                    )
                ;
            }
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    protected function createResponseConfig(string $path, array $config, array $defaultConfig): ResponseConfigInterface
    {
        if (!empty($defaultConfig))
        {
            if ($config[ConfigInterface::CONFIG_KEY_USE_DEFAULTS] ?? true)
            {
                $config = array_replace_recursive($defaultConfig, $config);
            }
        }

        $config['path'] = $path;
        return $this->createConfigObject(ResponseConfig::class, $config);
    }

    /**
     * @throws ExceptionInterface
     */
    protected function createRequestConfig(string $path, array $config, array $defaultConfig): RequestConfigInterface
    {
        $forAllDefaults = $defaultConfig['for_all'] ?? [];
        $byMethodDefaults = $defaultConfig['by_method'] ?? [];
        $defaults = [];

        $requestMethod = $config[RequestConfig::CONFIG_KEY_METHOD];
        foreach ($byMethodDefaults as $methodDefaults)
        {
            $applyFor = $methodDefaults['apply_for'] ?? [];
            if (empty($applyFor))
            {
                throw (new InvalidArgumentException())
                    ->setMessage(
                        'Invalid [%s/by-method] config. You must define [apply-for] config key and provide valid request method option(s) (POST, GET, etc.)',
                        static::CONFIG_KEY_DEFAULT_REQUEST_OPTIONS
                    );
            }
            if (in_array($requestMethod, $applyFor))
            {
                $useDefaults = $methodDefaults['use_defaults'] ?? true;
                $defaults = $methodDefaults;
                if ($useDefaults)
                {
                    $defaults = array_replace_recursive($forAllDefaults, $methodDefaults);
                }
            }
        }

        $useDefaults = $config[ConfigInterface::CONFIG_KEY_USE_DEFAULTS] ?? true;
        if ($useDefaults)
        {
            $config = array_replace_recursive($defaults, $config);
        }

        $config['path'] = $path;
        return $this->createConfigObject(RequestConfig::class, $config);
    }


    protected function getActionsList(array $config): array
    {
        $result = [];
        $path = [];

        $validateRequestConfig = function (string $path, mixed $config) {
            if (false === is_array($config))
            {
                throw (new InvalidArgumentException())
                    ->setMessage('Invalid action request config at path [%s], config must be an valid array', $path)
                ;
            }

            if (count(RequestConfig::CONFIG_REQUIRED_KEYS) !== count(array_intersect(RequestConfig::CONFIG_REQUIRED_KEYS, array_keys($config))))
            {
                throw (new InvalidArgumentException())
                    ->setMessage('Invalid action request config at path [%s], you must define all of theses keys [%s]', $path, implode('; ',RequestConfig::CONFIG_REQUIRED_KEYS))
                ;
            }

            //TODO validate method and URI Keys

        };

        $processActionsConfig = function (array $config) use (&$processActionsConfig, &$result, &$path, $validateRequestConfig) {
            foreach ($config as $key => $item)
            {
                if (array_key_exists(static::CONFIG_KEY_ACTION_REQUEST,$item))
                {
                    $requestConfig = $item[static::CONFIG_KEY_ACTION_REQUEST];
                    $path[] = $key;
                    $stringPath = implode('.', $path);
                    $validateRequestConfig($stringPath, $requestConfig);
                    $result[$stringPath] = $item;
                    $path = [];
                } else {
                    $path[] = $key;
                    $processActionsConfig($item);
                }
            }
        };

        $processActionsConfig($config);

        return $result;
    }


    /**
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     */
    protected function getConfig(array $content, string $configKey): array
    {
        if (false === array_key_exists($configKey, $content))
        {
            throw (new InvalidArgumentException())
                ->setMessage('Invalid config, config key [%s] does not exist',$configKey)
            ;
        }

        $config = $content[$configKey] ?? [];
        if (empty($config))
        {
            throw (new InvalidArgumentException())
                ->setMessage('Config defined by [%s] is empty', $configKey)
            ;
        }

        return $config;
    }

    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    protected function createConfigObject(string $class, array $config): ConfigInterface
    {
        $config = ArrayHelper::keysToCamelCase($config);
        return Hydrator::hydrate($class, $config);
    }
}