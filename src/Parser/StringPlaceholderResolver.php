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
use LetsCompose\Core\Interface\UniquePropertyListInterface;
use LetsCompose\Core\Object\Tools\UniquePropertyList;
use LetsCompose\Core\Tools\StringPlaceholderHelper;

class StringPlaceholderResolver implements StringPlaceholderResolverInterface
{
    private UniquePropertyListInterface $placeholdersNameValueList;

    protected const STRING_PLACEHOLDER_TOKEN = StringPlaceholderHelper::PLACEHOLDER_TOKEN_PERCENT;

    public function __construct(array $params = [])
    {
        $this->setPlaceholdersNameValueList($params);
    }

    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    public function resolvePlaceholders(string $content): string
    {
        $contentPlaceholders = $this->getPlaceholders($content);
        $contentPlaceholdersNameValueList = $this->preparePlaceholdersNameValueList($contentPlaceholders);

        return StringPlaceholderHelper::fillPlaceholders($content, $contentPlaceholdersNameValueList, self::STRING_PLACEHOLDER_TOKEN);
    }

    private function getPlaceholders(string $content): array
    {
        return StringPlaceholderHelper::getStringPlaceholders($content, self::STRING_PLACEHOLDER_TOKEN);
    }

    private function preparePlaceholdersNameValueList(array $params): array
    {
        $result = [];

        foreach ($this->placeholdersNameValueList as $param)
        {
            $result[$param->getName()] = $param->getValue();
        }

        return array_intersect_key($result, array_flip($params));
    }

    public function setPlaceholdersNameValueList(?array $nameValueList): self
    {
        $this->placeholdersNameValueList = UniquePropertyList::createFromArray($nameValueList);
        return $this;
    }


}