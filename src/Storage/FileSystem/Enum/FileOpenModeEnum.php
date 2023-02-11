<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\FileSystem\Enum;

use LetsCompose\Core\Storage\Actions\AbstractAction;
use LetsCompose\Core\Storage\Exception\FileNotFoundException;
use LetsCompose\Core\Storage\Exception\FileNotReadableException;
use LetsCompose\Core\Storage\Exception\FileNotWritableException;
use LetsCompose\Core\Storage\FileSystem\Resource\FileInterface;
use LetsCompose\Core\Storage\Resource\ResourceInterface;
use LetsCompose\Core\Tools\ExceptionHelper;

enum FileOpenModeEnum
{
    case WRITE;
    case READ;
    case APPEND;

    public function mode(): string
    {
        return match($this)
        {
            self::WRITE => 'w',
            self::READ => 'r',
            self::APPEND => 'a',
        };
    }

}