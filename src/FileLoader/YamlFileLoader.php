<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\FileLoader;

use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\InvalidArgumentException;
use LetsCompose\Core\Tools\ExceptionHelper;
use LetsCompose\Core\Tools\Storage\PathHelper;

class YamlFileLoader extends AbstractFileLoader
{
    public const SUPPORTED_FILE_TYPES = [
        'yaml',
        'yml'
    ];

    /**
     * @throws ExceptionInterface
     */
    public function getContents(string $path): string
    {
        $path = sprintf('%s/%s', $this->getPath(), $path);
        $path = PathHelper::normalize($path);

        if (!$this->supports($path))
        {
            ExceptionHelper::create(new InvalidArgumentException())
                ->message(
                    'Unsupported file type at path [%s], you can use only one of theses file types [%s]',
                    $path, implode(', ', self::SUPPORTED_FILE_TYPES)
                )
                ->throw();
        }

        return file_get_contents($path);

    }

    public function supports(string $path): bool
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        return \in_array($extension, self::SUPPORTED_FILE_TYPES, true);
    }
}