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
use LetsCompose\Core\HttpClient\Config\Action\ActionConfigInterface;
use LetsCompose\Core\HttpClient\Config\Request\RequestConfig;
use LetsCompose\Core\Tools\ArrayHelper;
use LetsCompose\Core\Tools\Data\Hydrator;
use LetsCompose\Core\Tools\ExceptionHelper;

class ConfigLoader implements ConfigLoaderInterface
{
    protected const CONFIG_KEY = 'http_client';
    protected const CONFIG_KEY_DEFAULT_REQUEST_OPTIONS = 'default_request_options';
    protected const CONFIG_KEY_RESPONSE_DEFAULTS = 'response_defaults';
    protected const CONFIG_KEY_EXCEPTION_DEFAULTS = 'exception_defaults';
    protected const CONFIG_KEY_ACTIONS = 'actions';
    protected const CONFIG_KEY_ACTION_REQUEST = 'request';

    /**
     * @throws ExceptionInterface
     */
    public function load(array $content): ConfigInterface
    {
        $clientConfig = new ClientConfig();

        $config = $this->getConfig($content, static::CONFIG_KEY);
        $actions = $this->loadActions($config);

        return $clientConfig;

    }

    /**
     * @return ActionConfigInterface[]
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     */
    protected function loadActions(array $clientConfig): array
    {
        $config = $this->getConfig($clientConfig, static::CONFIG_KEY_ACTIONS);
        $actions = $this->prepareActionsConfig($config);

        foreach ($actions as $path => $action)
        {
            $requestConfig = $action[static::CONFIG_KEY_ACTION_REQUEST];
            $defaultRequestOptions = $clientConfig[static::CONFIG_KEY_DEFAULT_REQUEST_OPTIONS];
            $forAllDefaults = $defaultRequestOptions['for_all'] ?? [];
            $byMethodDefaults = $defaultRequestOptions['by_method'] ?? [];
            $defaults = [];

            $requestMethod = $requestConfig[RequestConfig::CONFIG_KEY_METHOD];
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

            $useDefaults = $requestConfig[RequestConfig::CONFIG_KEY_USE_DEFAULTS] ?? false;
            if ($useDefaults)
            {
                $requestConfig = array_replace_recursive($defaults, $requestConfig);
            }

            $requestConfig = ArrayHelper::keysToCamelCase($requestConfig);
            $requestConfig = Hydrator::hydrate(RequestConfig::class, $requestConfig);
            dump($requestConfig);
        }

        return [];
    }

    protected function prepareActionsConfig(array $config): array
    {
        $requestKey = 'request';
        $result = [];
        $path = [];

        $validateRequestConfig = function (string $path, mixed $config) {
            if (false === is_array($config))
            {
                throw (new InvalidArgumentException())
                    ->setMessage('Invalid action request config at path [%s], config must be an valid array', $path);
            }

            if (count(RequestConfig::CONFIG_REQUIRED_KEYS) !== count(array_intersect(RequestConfig::CONFIG_REQUIRED_KEYS, array_keys($config))))
            {
                throw (new InvalidArgumentException())
                    ->setMessage('Invalid action request config at path [%s], you must define all of theses keys [%s]', $path, implode('; ',RequestConfig::CONFIG_REQUIRED_KEYS));
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
    public function getConfig(array $content, string $configKey): array
    {
        if (false === array_key_exists($configKey, $content))
        {
            ExceptionHelper::create(new InvalidArgumentException())
                ->message('Invalid config, config key [%s] does not exist',$configKey)
                ->throw();
        }

        $config = $content[$configKey] ?? [];
        if (empty($config))
        {
            ExceptionHelper::create(new InvalidArgumentException())
                ->message('Config defined by [%s] is empty', $configKey)
                ->throw();
        }

        return $config;
    }

}