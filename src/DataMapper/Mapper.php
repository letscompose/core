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
use LetsCompose\Core\Exception\NotSupportedException;
use LetsCompose\Core\Exception\NotUniqueException;
use LetsCompose\Core\Tools\Data\DataPropertyAccessor;
use LetsCompose\Core\Tools\ExceptionHelper;
use function is_array;
use function is_callable;
use function is_string;

class Mapper implements MapperInterface
{
    protected const CONFIG_KEY = 'mapping-config';
    protected const CONFIG_KEY_MAPPING = 'mapping-schema';
    protected const CONFIG_KEY_DEFAULT_OPTIONS = 'default-options';
    protected const CONFIG_KEY_OPTIONS_EXTEND = 'options-extend';
    protected const CONFIG_KEY_MAPPING_TEMPLATE = 'mapping-schema-template';
    protected const CONFIG_KEY_INPUT_OPTIONS = 'InputOptions';
    protected const CONFIG_KEY_OUTPUT_OPTIONS = 'OutputOptions';

    protected array $defaultMappingOptions = [];
    protected const DEFAULT_MAPPING_OPTIONS = [
        self::CONFIG_KEY_INPUT_OPTIONS => [
            'Collection' => false,
            'MappedKey'=> null,
            'StrictPropertyPath' => true,
        ],
        self::CONFIG_KEY_OUTPUT_OPTIONS => [
            'Object' => false,
        ],
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

        $checkConfigStructure(self::CONFIG_KEY, $mappingConfig);
        $checkConfigStructure(self::CONFIG_KEY_MAPPING, $mappingConfig[static::CONFIG_KEY]);

        $mappingConfig = $mappingConfig[static::CONFIG_KEY];
        $mappingOptions = $mappingConfig[static::CONFIG_KEY_DEFAULT_OPTIONS] ?? [];

        $this->mappingConfig = $mappingConfig;

        $this->defaultMappingOptions = array_replace_recursive(static::DEFAULT_MAPPING_OPTIONS, $mappingOptions);
        $optionsExtendConfig = $mappingConfig[static::CONFIG_KEY_OPTIONS_EXTEND] ?? [];

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
        $config = $this->getPropertyAtPath($configPath, $this->mappingConfig[static::CONFIG_KEY_MAPPING]);
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

        $mappingTemplatePath = $config['MappingTemplate'] ?? false;
        if ($mappingTemplatePath)
        {
            unset($config['MappingTemplate']);
            $mappingTemplate = $this->getPropertyAtPath($mappingTemplatePath, $this->mappingConfig[static::CONFIG_KEY_MAPPING_TEMPLATE]);
            $config = array_replace_recursive($mappingTemplate, $config);
        }

        $result = [];
        $inputOptions = $this->getConfigOptions($config, static::CONFIG_KEY_INPUT_OPTIONS);
        $this->exceptionOnUnsupportedOption($inputOptions, static::CONFIG_KEY_INPUT_OPTIONS);

        $outputOptions = $this->getConfigOptions($config, static::CONFIG_KEY_OUTPUT_OPTIONS);
        $this->exceptionOnUnsupportedOption($outputOptions, static::CONFIG_KEY_OUTPUT_OPTIONS);


        $mappingConfig = $config['Mapping'] ?? [];
        if (empty($mappingConfig))
        {
            ExceptionHelper::create(new InvalidArgumentException())
                ->message('You must define an valid Mapping config')
                ->throw();
        }

        if (is_string($inputOptions['MappedKey']) && !empty($data))
        {
            $data = $this->getPropertyAtPath($inputOptions['MappedKey'], $data);
        }

        if (true === $inputOptions['Collection'])
        {
            foreach ($data as $item)
            {
                $result[] = $this->applyMapping($mappingConfig, $inputOptions, $outputOptions, $item);
            }
        }
        else
        {
            $result = $this->applyMapping($mappingConfig, $inputOptions, $outputOptions, $data);
        }

        return $result;
    }

    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    protected function applyMapping(array $mappingConfig, array $inputOptions, array $outputOptions, array $data): array|object
    {
        if (false === is_array(current($mappingConfig)))
        {
            $data = $this->applyOptions($inputOptions, static::CONFIG_KEY_INPUT_OPTIONS, $data);
        }
        $data = $this->mapData($mappingConfig, $inputOptions, $data);
        $data = $this->applyOptions($outputOptions, static::CONFIG_KEY_OUTPUT_OPTIONS, $data);
        return $this->applyTransform($data, $inputOptions);
    }

    protected function applyOptions(array $mappingOptions, string $optionsGroup, array $data): array
    {
        $optionsExtend = $this->optionsExtend[$optionsGroup] ?? [];
        if (empty($optionsExtend))
        {
            return $data;
        }

        $mappingOptions = array_intersect_key($mappingOptions, $optionsExtend);

        foreach ($mappingOptions as $name => $value)
        {
            if (false === $value || null === $value)
            {
                continue;
            }
            $option = $optionsExtend[$name] ?? false;
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

    protected function getConfigOptions(array $config, string $configKey): array
    {
        $options = $config[$configKey] ?? [];
        return array_replace_recursive($this->defaultMappingOptions[$configKey], $options);
    }

    /**
     * @throws NotUniqueException
     * @throws MustImplementException
     * @throws ExceptionInterface
     */
    public function extendOptions(array $config): self
    {
        $registerOptions = function (string $group, array $optionsConfig): void
        {
            $optionsConfig = $optionsConfig[$group] ?? false;
            if (!is_array($optionsConfig))
            {
                return;
            }

            $configList = [];
            $defaults  = [
                'class' => null,
                'name' => null,
                'group' => $group,
                'priority' => null
            ];
            $defaultPriority = 0;

            foreach ($optionsConfig as $name => $optionConfig)
            {
                if (false === is_array($optionConfig) && false === is_string($optionConfig))
                {
                    ExceptionHelper::create(new NotSupportedException())
                        ->message('Not supported option config, option config must be an option class name or array with required [class] key')
                        ->throw();
                }
                $config = [
                    'name' => $name
                ];

                if (is_string($optionConfig))
                {
                    $config['class'] = $optionConfig;
                }
                elseif (is_array($optionConfig))
                {
                    $class = $optionConfig['class']  ?? null;
                    if (empty($class))
                    {
                        ExceptionHelper::create(new NotSupportedException())
                            ->message('Not supported option config, option config must be an option class name or array with required [class] key')
                            ->throw();
                    }
                    $config['class'] = $class;
                    $config['priority'] = $optionConfig['priority']  ?? null;
                }

                $config = array_replace($defaults, $config);
                if (false === is_int($config['priority']))
                {
                    $config['priority'] = $defaultPriority;
                    $defaultPriority++;
                }
                $configList[] = $config;
            }

            usort($configList, fn (array $a, array $b) => ($a['priority'] <=> $b['priority']));

            foreach ($configList as $config)
            {
                $this->registerOptionHandler($config['name'], $config['group'], $config['class']);
            }
        };

        $registerOptions(static::CONFIG_KEY_INPUT_OPTIONS, $config);
        $registerOptions(static::CONFIG_KEY_OUTPUT_OPTIONS, $config);

        return $this;
    }

    /**
     * @throws NotUniqueException
     * @throws MustImplementException
     * @throws ExceptionInterface
     */
    public function registerOptionHandler(string $name, string $group, string $class, int $priority = 1000): self
    {
        if (isset($this->optionsExtend[$group][$name]) || isset(static::DEFAULT_MAPPING_OPTIONS[$name]))
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
        $this->optionsExtend[$group][$name] = $option;
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
    protected function exceptionOnUnsupportedOption(array $options, string $optionsConfigKey): void
    {
        $optionsExtend = $this->optionsExtend[$optionsConfigKey] ?? [];
        $knownOptions = static::DEFAULT_MAPPING_OPTIONS[$optionsConfigKey] +  $optionsExtend;
        $unsupportedOptions = array_diff_key($options, $knownOptions);
        if ($unsupportedOptions)
        {
            ExceptionHelper::create(new NotExistsException())
                ->message(
                    'You try to use unknown mapper option(s) [%s] in [%s] config section. You can use only theses [%s] options',
                    implode(',', array_keys($unsupportedOptions)),
                    $optionsConfigKey,
                    implode(',', array_keys($knownOptions))
                )
                ->throw()
            ;
        }
    }
}