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
use LetsCompose\Core\FileLoader\FileLoaderInterface;
use Symfony\Component\Yaml\Yaml;

class YamlStringParser implements StringParserInterface
{
    /**
     * @var StringPlaceholderResolverInterface[]
     */
    protected array $placeHolderResolvers = [];

    protected FileLoaderInterface $fileLoader;

    protected const PARSER_BEHAVIOR_MAIN_SECTION = 'parser-behavior';

    protected const PARSER_BEHAVIOR_EXTENDS_SECTION = 'extends';

    protected const PARSER_BEHAVIOR_IMPORT_SECTION = 'imports';


    public function __construct(FileLoaderInterface $fileLoader)
    {
        $this->placeHolderResolvers = $this->getPlaceholderResolvers();
        $this->fileLoader = $fileLoader;
    }

    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    public function parse(string $content, ?array $contentPlaceholderParameters = []): array
    {

        $content = $this->parseContent($content);
        $content = json_encode($content);

        foreach ($this->placeHolderResolvers as $resolver)
        {
            $resolver->setPlaceholdersNameValueList($contentPlaceholderParameters);
            $content = $resolver->resolvePlaceholders($content);
        }

        return json_decode($content, true);
    }


    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    private function parseContent(string $content): array
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

        // process extends section
        $content = $this->processExtends($behaviorSection, $content);


        // process import section

        return $content;

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

    protected function processImports(array $content): array
    {
        return $content;
    }


    public function addParamResolver(StringPlaceholderResolverInterface $paramsResolver)
    {
        $this->placeHolderResolvers[] = $paramsResolver;
    }

    public function getPlaceholderResolvers(): array
    {
        return [
            new StringPlaceholderResolver()
        ];
    }

}