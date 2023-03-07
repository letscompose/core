<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\HttpClient\Option;

use LetsCompose\Core\HttpClient\Request\RequestInterface;

class MapKeysOption implements RequestOptionInterface
{
    public function process(RequestInterface $request): RequestInterface
    {
        return $request;
    }

    public function configure(mixed $config): self
    {
        // TODO: Implement configure() method.
        return $this;
    }

    public function supports(RequestInterface $request): bool
    {
        return true;
    }
}