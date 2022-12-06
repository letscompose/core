<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\FileSystem\Resource;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\InvalidArgumentException;
use LetsCompose\Core\Storage\Resource\AbstractResource;
use LetsCompose\Core\Tools\ExceptionHelper;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class AbstractFileSystemResource extends AbstractResource
{

    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    public function isOpen(): bool
    {
        $stream = $this->getStream();
        if (self::STATE_OPENED_STREAM === $this->getState())
        {
            if (is_resource($stream))
            {
                return true;
            }
            $this->setState(self::STATE_CLOSED_STREAM);
        }
        return false;
    }
}