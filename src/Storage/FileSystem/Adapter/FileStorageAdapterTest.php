<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\FileSystem\Adapter;

use Generator;
use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\InvalidArgumentException;
use LetsCompose\Core\Storage\AbstractResourceStorage;
use LetsCompose\Core\Storage\Adapter\AbstractAdapter;
use LetsCompose\Core\Storage\Exception\FileNotFoundException;
use LetsCompose\Core\Storage\Exception\FileNotReadableException;
use LetsCompose\Core\Storage\Exception\FileNotWritableException;
use LetsCompose\Core\Storage\FileSystem\LocalStorage;
use LetsCompose\Core\Storage\FileSystem\LocalResourceStorageInterface;
use LetsCompose\Core\Storage\FileSystem\Resource\File;
use LetsCompose\Core\Storage\FileSystem\Resource\FileInterface;
use LetsCompose\Core\Storage\Resource\ResourceInterface;
use LetsCompose\Core\Storage\Exception\PathNotFoundException;
use LetsCompose\Core\Storage\ResourceStorageInterface;
use LetsCompose\Core\Tools\ExceptionHelper;
use LetsCompose\Core\Tools\Storage\Path;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class FileStorageAdapterTest extends AbstractAdapter
{

}