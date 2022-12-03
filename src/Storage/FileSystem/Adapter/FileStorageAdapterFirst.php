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
use LetsCompose\Core\Storage\AbstractStorage;
use LetsCompose\Core\Storage\Exception\FileNotFoundException;
use LetsCompose\Core\Storage\Exception\FileNotReadableException;
use LetsCompose\Core\Storage\Exception\FileNotWritableException;
use LetsCompose\Core\Storage\FileSystem\LocalStorageFirst;
use LetsCompose\Core\Storage\FileSystem\LocalStorageInterface;
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
class FileStorageAdapterFirst extends AbstractStorage implements LocalStorageInterface
{
    /**
     * @var string[]
     */
    private const OPEN_MODE_MAP = [
        self::OPEN_MODE_READ => 'r',
        self::OPEN_MODE_WRITE => 'x',
        self::OPEN_MODE_RE_WRITE => 'w',
        self::OPEN_MODE_APPEND => 'a',
    ];

    /**
     * @param LocalStorageFirst $storage
     * @throws ExceptionInterface
     */
    public function __construct(private readonly LocalStorageFirst $storage)
    {
        $this->setRootPath($storage->getRootPath());
    }


    public function open(ResourceInterface $file, ?string $mode = self::OPEN_MODE_READ): FileInterface
    {
        if (false === $this->isExists($file))
        {
            ExceptionHelper::create(new FileNotFoundException())
                ->message('Can\'t open file at path [%s]. File does not exist on storage [%s]', $file->getPath(), $file->getStorageClass())
                ->throw()
            ;
        }

        switch ($mode) {
            case self::OPEN_MODE_READ:
                if (false === $this->isReadable($file)) {
                    ExceptionHelper::create(new FileNotReadableException())
                        ->message('Not readable file at path [%s] on storage [%s]', $file->getPath(), $file->getStorageClass())
                        ->throw();
                }
                break;
            case self::OPEN_MODE_WRITE:
            case self::OPEN_MODE_RE_WRITE:
            case self::OPEN_MODE_APPEND:
                if (false === $this->isWritable($file)) {
                    ExceptionHelper::create(new FileNotWritableException())
                        ->message('Not writable file at path [%s] on storage [%s]', $file->getPath(), $file->getStorageClass())
                        ->throw();
                }
                break;
        }

        $fullFilePath = $this->getFullPath($file);

        $stream = fopen($fullFilePath, self::OPEN_MODE_MAP[$mode]);

        return $file->setStream($stream);
    }

    /**
     * @inheritDoc
     */
    public function read(FileInterface|ResourceInterface $file, int $chunkSize = 1024): mixed
    {
        if (!$file->isOpen())
        {
            $file = $this->open($file, self::OPEN_MODE_READ);
        }
        $stream = $file->getStream();
        return !feof($stream) ? fread($stream, $chunkSize) : false;
    }

    /**
     * @param FileInterface|ResourceInterface $file
     * @return Generator
     */
    public function readLine(FileInterface|ResourceInterface $file): Generator
    {
        $line = null;
        while ($data = $this->read($file))
        {
            $i = 0;
            $length = strlen($data)-1;
            while ($i < $length)
            {
                $line .= $data[$i++];
                if ($line[-1] === PHP_EOL)
                {
                    yield $line;
                    $line = null;
                }
            }
        }
        yield $line;
    }


    /**
     * @inheritDoc
     */
    public function close(ResourceInterface $resource): ResourceInterface
    {
        $stream = $resource->getStream();
        fflush($stream);
        fclose($stream);
        $resource->setState(ResourceInterface::STATE_CLOSED_STREAM);
        return $resource;
    }

    /**
     * @inheritDoc
     */
    public function isExists(ResourceInterface $resource): bool
    {
        $fullFilePath = $this->getFullPath($resource);
        return file_exists($fullFilePath) && is_file($fullFilePath);
    }

    /**
     * @inheritDoc
     */
    public function isReadable(ResourceInterface $resource): bool
    {
        $fullFilePath = $this->getFullPath($resource);
        return is_readable($fullFilePath);
    }

    /**
     * @inheritDoc
     */
    public function isWritable(ResourceInterface $resource): bool
    {
        $fullFilePath = $this->getFullPath($resource);
        return is_writable($fullFilePath);
    }


    /**
     * @param string $rootPath
     * @return $this
     * @throws ExceptionInterface
     */
    public function setRootPath(string $rootPath): self
    {
        if (false === Path::isAbsolute($rootPath))
        {
            ExceptionHelper::create(new InvalidArgumentException())
                ->message('Invalid storage root path [%s]. Path must beginning with "/"', $rootPath)
                ->throw()
                ;
        }

        $realPath = realpath($rootPath);
        if (false === $realPath)
        {
            ExceptionHelper::create(new PathNotFoundException())
                ->message('Invalid storage root path [%s]. Path does not found "/"', $rootPath)
                ->throw()
            ;
        }

        return parent::setRootPath($realPath);
    }

    /**
     * @param ResourceInterface $resource
     * @return string
     */
    public function getFullPath(ResourceInterface $resource): string
    {
        $path = sprintf('%s/%s', $this->getRootPath(), $resource->getPath());

        return Path::normalize($path);
    }

    /**
     * @param string $path
     * @return FileInterface
     * @throws ExceptionInterface
     */
    public function initResource(string $path): FileInterface
    {
        return
            (new File())
                ->setPath($path)
                ->setStorageClass($this->storage::class);
    }

    /**
     * @param FileInterface $file
     * @return FileInterface
     * @throws ExceptionInterface
     */
    public function refreshFileInfo(FileInterface $file): FileInterface
    {
        if (false === $this->isExists($file))
        {
            ExceptionHelper::create(new FileNotFoundException())
                ->message('File at path [%s] does not exist on storage [%s]', $file->getPath(), $file->getStorageClass())
                ->throw()
            ;
        }
        $filePath = $this->getFullPath($file);
        $fileMimeType = $this->detectFileMimeType($file);
        $fileSize = filesize($filePath);

        $file->setMimeType($fileMimeType);
        $file->setSize($fileSize);

        return $file;
    }

    /**
     * @inheritDoc
     */
    public function write(ResourceInterface $resource, mixed $data): mixed
    {
        // TODO: Implement write() method.
    }

    /**
     * @inheritDoc
     */
    public function remove(ResourceInterface $resource): ResourceInterface
    {
        // TODO: Implement remove() method.
    }

    /**
     * @param FileInterface $file
     * @return string|null
     */
    private function detectFileMimeType(FileInterface $file): ?string
    {
        $fullFilePath = $this->getFullPath($file);
        return \mime_content_type($fullFilePath) ?? null;
    }
}