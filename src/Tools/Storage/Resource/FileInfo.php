<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Tools\Storage\Resource;

use DateTimeInterface;
use LetsCompose\Core\Tools\System\SystemGroupInfoInterface;
use LetsCompose\Core\Tools\System\SystemUserInfoInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class FileInfo extends AbstractResourceInfo implements FileInfoInterface
{
    private int $size = 0;

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): FileInfo
    {
        $this->size = $size;
        return $this;
    }
}