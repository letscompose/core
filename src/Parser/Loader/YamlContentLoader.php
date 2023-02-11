<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Parser\Loader;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\InvalidArgumentException;
use LetsCompose\Core\FileLoader\YamlFileLoader;
use LetsCompose\Core\Parser\Config\SourceImportConfigInterface;
use LetsCompose\Core\Parser\YamlContentParser;
use LetsCompose\Core\Tools\ExceptionHelper;

class YamlContentLoader implements ContentLoaderInterface
{
    public function __construct(private readonly YamlContentParser $parser)
    {
    }

    /**
     * @throws ExceptionInterface
     */
    public function load(string $source): array
    {
        if (!$this->supports($source)) {
            ExceptionHelper::create(new InvalidArgumentException())
                ->message(
                    'This loader can\'t be use for this source [%s]',
                    $source
                )
                ->throw();
        }

        return $this->parser->parseFile($source);
    }

    public function supports(string $source): bool
    {
        $extension = pathinfo($source, PATHINFO_EXTENSION);
        return \in_array($extension, YamlFileLoader::SUPPORTED_FILE_TYPES, true);
    }

    public function setConfig(array $config): self
    {
        // TODO: Implement setConfig() method.
        return $this;
    }

    public function getConfig(): array
    {
        // TODO: Implement getConfig() method.
    }
}