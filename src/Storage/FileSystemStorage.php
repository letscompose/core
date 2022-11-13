<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class FileSystemStorage implements FileLocatorInterface
{
    /**
     * @param string|null $path
     */
    private function __construct
    (
        private ?string $path = null
    )
    {

    }

    public function locate(string $filePath)
    {
        // TODO: Implement locate() method.
    }

    public static function factory(string $path)
    {

    }

    /**
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param string|null $path
     */
    public function setPath(?string $path): void
    {
        $this->path = $path;
    }


}