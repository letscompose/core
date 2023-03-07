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

interface ResponseContentInterface
{
    public function getContent(): mixed;
    public function setContent(mixed $content): self;

    public function getRawContent(): mixed;
    public function setRawContent(mixed $content): self;
}