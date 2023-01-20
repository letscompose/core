<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\Resource;

use DateTimeInterface;
use LetsCompose\Core\Interface\UserRefInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
interface ResourceInfoInterface
{
    public function getOwner(): UserRefInterface;
    public function getCreatedAt(): DateTimeInterface;
    public function getAccessedAt(): ?DateTimeInterface;
    public function getUpdatedAt(): ?DateTimeInterface;
}