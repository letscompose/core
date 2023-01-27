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

class YamlStringParser implements StringParserInterface
{
    /**
     * @var StringPlaceholderResolverInterface[]
     */
    protected array $paramsResolvers = [];

    public function __construct()
    {
        $this->paramsResolvers = $this->getPlaceholderResolvers();
    }

    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    public function parse(string $content, ?array $contentPlaceholderParameters = []): array
    {
        foreach ($this->paramsResolvers as $resolver)
        {
            $resolver->setPlaceholdersNameValueList($contentPlaceholderParameters);
            $content = $resolver->resolvePlaceholders($content);
        }

        // process extends section

        // process import section


        dump($content);
        die();
        return $content;

    }

    public function addParamResolver(StringPlaceholderResolverInterface $paramsResolver)
    {
        $this->paramsResolvers[] = $paramsResolver;
    }

    public function getPlaceholderResolvers(): array
    {
        return [
            new StringPlaceholderResolver()
        ];
    }

}