<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\HttpClient\Response;

class ResponseContent implements ResponseContentInterface
{
    private mixed $content;
    private mixed $rawContent;

    public function getContent(): mixed
    {
        return $this->content;
    }

    public function setContent(mixed $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getRawContent(): mixed
    {
        return $this->rawContent;
    }

    public function setRawContent(mixed $content): self
    {
        $this->rawContent = $content;
        return $this;
    }
}
