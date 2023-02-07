<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\DataMapper;

use LetsCompose\Core\DataMapper\Options\OptionInterface;
use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\InvalidArgumentException;
use LetsCompose\Core\Exception\MustImplementException;
use LetsCompose\Core\Exception\NotExistsException;
use LetsCompose\Core\Exception\NotUniqueException;
use LetsCompose\Core\Tools\Data\DataPropertyAccessor;
use LetsCompose\Core\Tools\ExceptionHelper;
use function is_array;
use function is_callable;
use function is_string;

class Mapper implements MapperInterface
{
    protected const MAPPING_CONFIG_KEY = 'mapping-config';
    protected const MAPPING_STRUCTURE_KEY = 'schema';
    protected const MAPPING_STRUCTURE_OPTIONS_KEY = 'options';
    protected const MAPPING_STRUCTURE_OPTIONS_EXTEND_KEY = 'options-extend';
    protected const MAPPING_STRUCTURE_TEMPLATE_KEY = 'template';
    protected array $defaultMappingOptions = [];
    protected const DEFAULT_MAPPING_OPTIONS = [
        'Object' => false,
        'Collection' => false,
        'MappedKey'=> null,
        'MappingTemplate'=>null,
        'StrictPropertyPath' => true,
    ];

    /**
     * @var OptionInterface[]
     */
    protected array $optionsExtend = [];

    /**
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     */
    public function __construct(protected array $mappingConfig)
    {
        $checkConfigStructure = function (string $configKey, array $config)
        {
            $config =  $config[$configKey] ?? false;
            if (!$config)
            {
                ExceptionHelper::create(new InvalidArgumentException())
                    ->message('can\'t create instance of [%s], invalid mapping config, key [%s] must be defined', get_called_class(), $configKey)
                    ->throw();
            }
        };

        $checkConfigStructure(self::MAPPING_CONFIG_KEY, $mappingConfig);
        $checkConfigStructure(self::MAPPING_STRUCTURE_KEY, $mappingConfig[static::MAPPING_CONFIG_KEY]);

        $mappingConfig = $mappingConfig[static::MAPPING_CONFIG_KEY];
        $mappingOptions = $mappingConfig[static::MAPPING_STRUCTURE_OPTIONS_KEY] ?? [];

        $this->mappingConfig = $mappingConfig;

        $this->defaultMappingOptions = array_replace_recursive(static::DEFAULT_MAPPING_OPTIONS, $mappingOptions);
        $optionsExtendConfig = $mappingConfig[static::MAPPING_STRUCTURE_OPTIONS_EXTEND_KEY] ?? [];

        if ($optionsExtendConfig)
        {
            $this->extendOptions($optionsExtendConfig);
        }

    }

    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    public static function create(array $mappingConfig): MapperInterface
    {
        return new static($mappingConfig);
    }

    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    public function map(string $configPath, object|array $data): object|array
    {
        $config = $this->getPropertyAtPath($configPath, $this->mappingConfig[static::MAPPING_STRUCTURE_KEY]);
        if (empty($config))
        {
            ExceptionHelper::create(new InvalidArgumentException())
                ->message('can\'t get mapping config for [%s]', $configPath)
                ->throw();
        }

        return $this->process($config, $data);
    }


    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    protected function process(array $config, array|object $data): array|object
    {

        $mappingTemplatePath = $config['Options']['MappingTemplate'] ?? false;
        if ($mappingTemplatePath)
        {
            unset($config['Options']['MappingTemplate']);
            $mappingTemplate = $this->getPropertyAtPath($mappingTemplatePath, $this->mappingConfig[static::MAPPING_STRUCTURE_TEMPLATE_KEY]);
            $config = array_replace_recursive($mappingTemplate, $config);
        }

        $result = [];
        $mappingOptions = $this->getConfigOptions($config);
        $mappingConfig = $config['Mapping'] ?? [];
        $this->exceptionOnUnsupportedOption($mappingOptions);

        if (empty($mappingConfig))
        {
            ExceptionHelper::create(new InvalidArgumentException())
                ->message('You must define an valid Mapping config')
                ->throw();
        }

        if (is_string($mappingOptions['MappedKey']) && !empty($data))
        {
            $data = $this->getPropertyAtPath($mappingOptions['MappedKey'], $data);
        }

        if (true === $mappingOptions['Collection'])
        {
            foreach ($data as $item)
            {
                $result[] = $this->applyMapping($mappingConfig, $mappingOptions,$item);
            }
        }
        else
        {
            $result = $this->applyMapping($mappingConfig, $mappingOptions, $data);
        }

        return $result;
    }

    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    protected function applyMapping(array $mappingConfig, array $mappingOptions, array $data): array|object
    {
        $result = $this->applyOptions($mappingOptions, $data);
        $result = $this->mapData($mappingConfig, $mappingOptions, $result);
        return $this->applyTransform($result, $mappingOptions);
    }

    protected function applyOptions(array $mappingOptions, array $data): array
    {
        foreach ($mappingOptions as $name => $value)
        {
            if (false === $value || null === $value)
            {
                continue;
            }
            $option = $this->optionsExtend[$name] ?? false;
            if ($option)
            {
                $option->setConfig($value);
                if ($option->supports($name))
                {
                    $data = $option->process($data);
                }
            }
        }
        return $data;
    }

    protected function applyTransform(array $data, array $options): array|object
    {
        if (empty($options['Object']))
        {
            return $data;
        }

        $objectClass = $options['Object'];

        if (is_string($objectClass))
        {
            return new $objectClass($data);
        }
        elseif (is_array($objectClass) && is_callable($objectClass))
        {
            return call_user_func($objectClass, $data);
        }

        return $data;
    }


    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    protected function mapData(array $mappingConfig, array $options, array $data): array {
            $result = [];
            if (empty($data)) {
                return $result;
            }
            foreach ($mappingConfig as $localKey => $remoteKey) {
                if (is_array($remoteKey))  {
                    $result[$localKey] = $this->process($remoteKey, $data);
                } else {
                    /**
                     * when StrictPropertyPath option is set to false mapper will fill node value with null
                     * otherwise we re-throw an exception
                     * example when read 'target.company.name' mapped path and 'company' not defined
                     */
                    $value = null;
                    try {
                        $value = $this->getPropertyAtPath($remoteKey, $data);
                    } catch (\Throwable $exception) {
                        if (true === $options['StrictPropertyPath']) {
                            throw $exception;
                        }
                    }
                    $result[$localKey] = $value;
                }
            }
            return $result;
    }

    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    protected function getPropertyAtPath(string $configPath, object|array $data): mixed
    {
        return DataPropertyAccessor::getPropertyAtPath($configPath, $data);
    }

    protected function getConfigOptions($config): array
    {
        if (false === array_key_exists('Options', $config)) {
            return $this->defaultMappingOptions;
        }
        return array_replace_recursive($this->defaultMappingOptions, $config['Options']);
    }

    /**
     * @throws NotUniqueException
     * @throws MustImplementException
     * @throws ExceptionInterface
     */
    public function extendOptions(array $config): self
    {
        foreach ($config as $name => $class)
        {
            $this->registerOptionHandler($name, $class);
        }
        return $this;
    }

    /**
     * @throws NotUniqueException
     * @throws MustImplementException
     * @throws ExceptionInterface
     */
    public function registerOptionHandler(string $name, string $class): self
    {
        if (isset($this->optionsExtend[$name]) || isset(static::DEFAULT_MAPPING_OPTIONS[$name]))
        {
            ExceptionHelper::create(new NotUniqueException())
                ->message('You try to define already defined Mapping option [%s]', $name)
                ->throw();
        }

        $option = new $class;
        if (!$option instanceof OptionInterface)
        {
            ExceptionHelper::create(new InvalidArgumentException())
                ->message('You must define an valid Mapping config')
                ->throw();
        }
        $option->setName($name);
        $this->optionsExtend[$name] = $option;

        return $this;
    }

    /**
     * @throws NotExistsException
     * @throws ExceptionInterface
     */
    protected function getOptionHandler(string $name): OptionInterface
    {
        $option = $this->optionsExtend[$name] ?? false;
        if (!$option)
        {
            ExceptionHelper::create(new NotUniqueException())
                ->message('You try to get not defined Mapping option [%s]', $name)
                ->throw();
        }
        return $option;
    }

    /**
     * @throws ExceptionInterface
     * @throws NotExistsException
     */
    protected function exceptionOnUnsupportedOption(array $options): void
    {
        $knownOptions = static::DEFAULT_MAPPING_OPTIONS + $this->optionsExtend;
        $unsupportedOptions = array_diff_key($options, $knownOptions);
        if ($unsupportedOptions)
        {
            ExceptionHelper::create(new NotExistsException())
                ->message(
                    'You try to use not  unknown mapper option(s) [%s], you must use one of theses [%s]',
                    implode(',', array_keys($unsupportedOptions)),
                    implode(',', array_keys($knownOptions))
                )
                ->throw()
            ;
        }
    }
}