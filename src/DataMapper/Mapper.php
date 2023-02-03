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

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\InvalidArgumentException;
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
    protected const MAPPING_STRUCTURE_TEMPLATE_KEY = 'template';
    protected array $defaultMappingOptions = [];

    protected const DEFAULT_MAPPING_OPTIONS = [
        'Object' => false,
        'Collection' => false,
        'MappedKey'=> null,
        'MappingTemplate'=>null,
        'StrictPropertyPath' => true,
        'StripEmptyKeys' => true,
        'InputDataTransformers' => [],
        'OutputDataTransformers' => []
    ];

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
        $options = $this->getConfigOptions($config);
        $mapping = $config['Mapping'] ?? [];


        if (empty($mapping))
        {
            ExceptionHelper::create(new InvalidArgumentException())
                ->message('You must define an valid Mapping config')
                ->throw();
        }

        if (is_string($options['MappedKey']) && !empty($data))
        {
            $data = $this->getPropertyAtPath($options['MappedKey'], $data);
        }

        if (true === $options['Collection'])
        {
            foreach ($data as $item)
            {
                $result[] = $this->applyMapping($mapping, $options, $item);
            }
        }
        else
        {
            $result = $this->applyMapping($mapping, $options, $data);
        }

        return $result;
    }

    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    protected function applyMapping(array $mapping, array $options, array $data): array|object
    {
        $rs = $this->mapData($mapping, $data, $options);
        return $this->transform($rs, $options);
    }


    protected function transform(array $data, array $options): array|object
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
    protected function mapData(array $mappingConfig, array $data, array $options): array {
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
}