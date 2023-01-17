<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Tools\Storage;

use DateTimeInterface;
use LetsCompose\Core\Tools\System\SystemGroupInfoInterface;
use LetsCompose\Core\Tools\System\SystemUserInfoInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
interface FileInfoInterface
{
    public function getOwner(): SystemUserInfoInterface;
    public function getGroup(): SystemGroupInfoInterface;
    public function getSize(): int;
    public function getAccessedAt(): DateTimeInterface;
    public function getUpdatedAt(): DateTimeInterface;
    public function getCreatedAt(): DateTimeInterface;
}