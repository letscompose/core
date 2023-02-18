<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\DataMapper\Service;

class ServiceConfig extends AbstractServiceConfig
{
    protected ?string $implements = null;

    protected ?string $call = null;

    public function getImplements(): ?string
    {
        return $this->implements;
    }

    public function setImplements(?string $implements): self
    {
        $this->implements = $implements;
        return $this;
    }

    public function getCall(): ?string
    {
        return $this->call;
    }

    public function setCall(?string $call): self
    {
        $this->call = $call;
        return $this;
    }
}