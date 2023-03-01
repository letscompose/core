<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Parser;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\InvalidArgumentException;
use LetsCompose\Core\Exception\MustImplementException;
use LetsCompose\Core\Exception\NotFoundException;
use LetsCompose\Core\FileLoader\FileLoaderInterface;
use LetsCompose\Core\Parser\Config\SourceImportConfig;
use LetsCompose\Core\Parser\Config\SourceImportConfigInterface;
use LetsCompose\Core\Parser\Config\SourceLoaderConfig;
use LetsCompose\Core\Parser\Loader\ContentLoaderInterface;
use LetsCompose\Core\Parser\Loader\YamlContentLoader;
use LetsCompose\Core\Tools\ExceptionHelper;
use Symfony\Component\Yaml\Yaml;

class YamlContentParser implements StringParserInterface
{
    /**
     * @var StringPlaceholderResolverInterface[]
     */
    protected array $placeholderResolvers = [];

    protected FileLoaderInterface $fileLoader;

    /**
     * @var ContentLoaderInterface[]
     */
    protected array $contentLoaders = [];

    protected array $parameters = [];

    protected array $contentPlaceholderParameters = [];

    protected const PARSER_BEHAVIOR_MAIN_SECTION = 'parser-behavior';

    protected const PARSER_BEHAVIOR_EXTENDS_SECTION = 'extends';

    protected const PARSER_BEHAVIOR_PARAMETERS_SECTION = 'parameters';

    protected const PARSER_BEHAVIOR_IMPORT_SECTION = 'imports';

    public function __construct(FileLoaderInterface $fileLoader)
    {
        $this->setPlaceholderResolvers(
            [
                new StringPlaceholderResolver()
            ]
        );

        $this->setContentLoaders(
            [
                new YamlContentLoader($this)
            ]
        );

        $this->fileLoader = $fileLoader;
    }


    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    public function parse(string $content): array
    {
        $content = $this->parseContent($content);
        $content = json_encode($content);
        $content = $this->resolveContentPlaceholders($content);
        return json_decode($content, true);
    }

    /**
     * @param string $file
     * @return array
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     */
    public function parseFile(string $file): array
    {
        $content = $this->fileLoader->getContents($file);
        return $this->parse($content);
    }

    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    protected function parseContent(string $content): array
    {
        $content = Yaml::parse($content);
        return $this->processBehavior($content);
    }

    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    protected function processBehavior(array $content): array
    {
        // check if content has behavior section to process
        $behaviorSection = $content[static::PARSER_BEHAVIOR_MAIN_SECTION] ?? false;
        if (!$behaviorSection)
        {
            return $content;
        }

        $behaviorSection = $this->resolveBehaviorPlaceholders($behaviorSection);

        // process extends section
        $content = $this->processExtends($behaviorSection, $content);


        // process import section
        $content = $this->processImports($behaviorSection, $content);

        // process parameters section
        $this->processParameters($behaviorSection);

        return $content;
    }


    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    protected function resolveBehaviorPlaceholders(array $behaviorSection)
    {
        $behaviorSection = json_encode($behaviorSection);
        $behaviorSection = $this->resolveContentPlaceholders($behaviorSection);
        return json_decode($behaviorSection, true);
    }


    protected function resolveContentPlaceholders(string $content): string
    {
        foreach ($this->placeholderResolvers as $resolver)
        {
            $content = $resolver->resolvePlaceholders($content);
        }
        return $content;
    }

    protected function processParameters($behaviorSection): void
    {
        $parametersSection = $behaviorSection[static::PARSER_BEHAVIOR_PARAMETERS_SECTION] ?? false;
        if (!$parametersSection)
        {
            return;
        }

        $parameters = array_replace($parametersSection, $this->getContentPlaceholderParameters());
        $this->setContentPlaceholderParameters($parameters);
    }


    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    protected function processExtends(array $behaviorSection, array $content): array
    {
        $extendsSection = $behaviorSection[static::PARSER_BEHAVIOR_EXTENDS_SECTION] ?? false;
        if (!$extendsSection)
        {
            return $content;
        }

        foreach ($extendsSection as $file)
        {
            $extendContent = $this->fileLoader->getContents($file);
            $extendContent = $this->parseContent($extendContent);
            $content = array_replace_recursive($extendContent, $content);
        }

        return $content;
    }

    /**
     * @throws MustImplementException
     * @throws ExceptionInterface
     * @throws NotFoundException
     */
    protected function processImports(array $behaviorSection, array $content): array
    {
        $importSection = $behaviorSection[static::PARSER_BEHAVIOR_IMPORT_SECTION] ?? false;
        if (!$importSection)
        {
            return $content;
        }

        foreach ($importSection as $importConfig)
        {
            $importConfig = $this->normalizeSourceImportConfig($importConfig);
            $importedContent = $this->importSource($importConfig);
            $content = array_replace_recursive($content, $importedContent);
        }

        return $content;
    }


    /**
     * @throws ExceptionInterface
     * @throws MustImplementException
     * @throws NotFoundException
     */
    protected function importSource(SourceImportConfigInterface $sourceImportConfig): array
    {
        $configData = [];
        if ($sourceImportConfig->hasLoaderConfig())
        {
            $loaderConfig = $sourceImportConfig->getSourceLoaderConfig();
            $loaderClass = $loaderConfig->getClass();
            $configData = $loaderConfig->getConfig();
            if (!$this->hasContentLoader($loaderClass))
            {
                $loaderInstance = new $loaderClass();
                if (!$loaderInstance instanceof ContentLoaderInterface)
                {
                    ExceptionHelper::create(new MustImplementException())
                        ->setMessage('Content loader [%s] must implement interface [%s]', $loaderClass, ContentLoaderInterface::class)
                        ->throw();
                }
                $this->addContentLoader($loaderInstance);
            }
        }

        foreach ($this->contentLoaders as $loader)
        {
            if ($loader->supports($sourceImportConfig->getSource()))
            {
                $loader->setConfig($configData);
                return $loader->load($sourceImportConfig->getSource());
            }
        }

        ExceptionHelper::create(new NotFoundException())
            ->setMessage('Can\'t import source [%s], without valid content loader', $sourceImportConfig->getSource())
            ->throw();
    }


    /**
     * @param string|array $importConfig
     * @return SourceImportConfig
     * @throws ExceptionInterface
     */
    private function normalizeSourceImportConfig(string|array $importConfig): SourceImportConfig
    {
        $sourceConfig = new SourceImportConfig();

        if (is_string($importConfig))
        {
            $sourceConfig->setSource($importConfig);
            return $sourceConfig;
        }

        $source = $importConfig['source'] ?? false;
        $sourceLoaderClassOrConfig = $importConfig['loader'] ?? null;

        if (!$source)
        {
            ExceptionHelper::create(new InvalidArgumentException())
                ->setMessage('Invalid import config, minimum you must define [source] key')
                ->throw()
            ;
        }
        $sourceConfig->setSource($source);

        if (!$sourceLoaderClassOrConfig)
        {
            return $sourceConfig;
        }

        $sourceLoaderConfig = new SourceLoaderConfig();
        if (is_string($sourceLoaderClassOrConfig))
        {
            $sourceLoaderClassConfig = $importConfig['config'] ?? [];
            $sourceLoaderConfig->setClass($sourceLoaderClassOrConfig);
            $sourceLoaderConfig->setConfig($sourceLoaderClassConfig);
            $sourceConfig->setSourceLoaderConfig($sourceLoaderConfig);
            return $sourceConfig;
        }

        if (is_array($sourceLoaderClassOrConfig))
        {
            $class = $sourceLoaderClassOrConfig['class'] ?? false;
            $config = $sourceLoaderClassOrConfig['config'] ?? [];

            if (!is_string($class) || !$class)
            {
                ExceptionHelper::create(new InvalidArgumentException())
                    ->setMessage('Invalid import config, loader class must be an valid object class')
                    ->throw()
                ;
            }

            $sourceLoaderConfig->setClass($class);
            $sourceLoaderConfig->setConfig($config);
            $sourceConfig->setSourceLoaderConfig($sourceLoaderConfig);
            return $sourceConfig;
        }

        ExceptionHelper::create(new InvalidArgumentException())
            ->setMessage('Invalid import config, [loader] key must be an string or valid import config object')
            ->throw()
        ;
    }

    /**
     * @return StringPlaceholderResolverInterface[]
     */
    public function getPlaceholderResolvers(): array
    {
        return $this->placeholderResolvers;
    }

    /**
     * @return ContentLoaderInterface[]
     */
    public function getContentLoaders(): array
    {
        return $this->contentLoaders;
    }

    protected function hasContentLoader(string $contentLoaderClass): bool
    {
        return isset($this->contentLoaders[$contentLoaderClass]);
    }

    /**
     * @param ContentLoaderInterface[] $contentLoaders
     */
    public function setContentLoaders(array $contentLoaders): self
    {
        $this->contentLoaders = [];
        foreach ($contentLoaders as $contentLoader)
        {
            $this->addContentLoader($contentLoader);
        }
        return $this;
    }

    protected function addContentLoader(ContentLoaderInterface $contentLoader)
    {
        $this->contentLoaders[$contentLoader::class] = $contentLoader;
    }

    /**
     * @return array
     */
    public function getContentPlaceholderParameters(): array
    {
        return $this->contentPlaceholderParameters;
    }

    /**
     * @param array $contentPlaceholderParameters
     * @return YamlContentParser
     */
    public function setContentPlaceholderParameters(array $contentPlaceholderParameters): self
    {

        foreach ($this->placeholderResolvers as $placeholderResolver)
        {
            $placeholderResolver->setPlaceholdersNameValueList($contentPlaceholderParameters);
        }

        $this->contentPlaceholderParameters = $contentPlaceholderParameters;
        return $this;
    }

    /**
     * @param StringPlaceholderResolverInterface[] $placeholderResolvers
     */
    public function setPlaceholderResolvers(array $placeholderResolvers): self
    {
        $this->placeholderResolvers = [];
        foreach ($placeholderResolvers as $resolver)
        {
            $this->addPlaceholderResolver($resolver);
        }
        return $this;
    }

    public function addPlaceholderResolver(StringPlaceholderResolverInterface $placeholderResolver): self
    {
        $this->placeholderResolvers[$placeholderResolver::class] = $placeholderResolver;
        return $this;
    }
}