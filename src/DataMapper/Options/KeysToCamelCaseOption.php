<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\DataMapper\Options;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\InvalidArgumentException;
use LetsCompose\Core\Tools\ArrayHelper;

class KeysToCamelCaseOption implements OptionInterface
{
    private string $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return KeysToCamelCaseOption
     */
    public function setName(string $name): KeysToCamelCaseOption
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    public function process(array $data): array
    {
        return ArrayHelper::keysToCamelCase($data);
    }

    public function setConfig(mixed $config): self
    {
        return $this;
    }

    public function supports(string $name): bool
    {
        return $name === $this->name;
    }


}