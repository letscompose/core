<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\FileSystem;

use Generator;
use LetsCompose\Core\Exception\ExceptionInterface;
use LetsCompose\Core\Exception\InvalidArgumentException;
use LetsCompose\Core\Storage\AbstractStorage;
use LetsCompose\Core\Storage\Exception\FileNotFoundException;
use LetsCompose\Core\Storage\Exception\FileNotReadableException;
use LetsCompose\Core\Storage\Exception\FileNotWritableException;
use LetsCompose\Core\Storage\Resource\ResourceInterface;
use LetsCompose\Core\Storage\Exception\PathNotFoundException;
use LetsCompose\Core\Tools\ExceptionHelper;
use LetsCompose\Core\Tools\Storage\Path;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class FileSystemStorage extends AbstractStorage implements FileSystemStorageInterface
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
     * @param FileSystemStorageStorageConfig $config
     * @throws ExceptionInterface
     */
    public function __construct(FileSystemStorageStorageConfig $config)
    {
        $this->setRootPath($config->getRootPath());
    }

    /**
     * @inheritDoc
     * @throws ExceptionInterface
     */
    public function open(string $path, ?string $mode = self::OPEN_MODE_MAP[self::OPEN_MODE_READ]): File
    {
        $file = $this->createFileObject($path);

        if (false === $this->fileExists($file))
        {
            ExceptionHelper::create(new FileNotFoundException())
                ->message('Can\'t open file at path [%s]. File does not exist on storage [%s]', $file->getPath(), $file->getStorageClass())
                ->throw()
            ;
        }

        switch ($mode) {
            case self::OPEN_MODE_READ:
                if (false === $this->isFileReadable($file)) {
                    ExceptionHelper::create(new FileNotReadableException())
                        ->message('Not readable file at path [%s] on storage [%s]', $file->getPath(), $file->getStorageClass())
                        ->throw();
                }
                break;
            case self::OPEN_MODE_WRITE:
            case self::OPEN_MODE_RE_WRITE:
            case self::OPEN_MODE_APPEND:
                if (false === $this->isFileWritable($file)) {
                    ExceptionHelper::create(new FileNotWritableException())
                        ->message('Not writable file at path [%s] on storage [%s]', $file->getPath(), $file->getStorageClass())
                        ->throw();
                }
                break;
        }

        $fullFilePath = $this->getFullFilePath($file);

        $stream = fopen($fullFilePath, $mode);

        return $file->setStream($stream);
    }

    /**
     * @inheritDoc
     */
    public function read(FileInterface|ResourceInterface $file, int $chunkSize = 1024): mixed
    {
        if (!$file->isOpen())
        {
            $file = $this->open($file->getPath(), self::OPEN_MODE_READ);
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
    public function close(ResourceInterface $resource): File
    {
        // TODO: Implement close() method.
    }

    /**
     * @inheritDoc
     */
    public function fileExists(FileInterface $file): bool
    {
        $fullFilePath = $this->getFullFilePath($file);
        return file_exists($fullFilePath) && is_file($fullFilePath);
    }

    /**
     * @inheritDoc
     */
    public function isFileReadable(FileInterface $file): bool
    {
        $fullFilePath = $this->getFullFilePath($file);
        return is_readable($fullFilePath);
    }

    /**
     * @inheritDoc
     */
    public function isFileWritable(FileInterface $file): bool
    {
        $fullFilePath = $this->getFullFilePath($file);
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
     * @param File $file
     * @return string
     */
    public function getFullFilePath(File $file): string
    {
        $path = sprintf('%s/%s', $this->getRootPath(), $file->getPath());

        return Path::normalize($path);
    }

    /**
     * @param string $path
     * @return File
     * @throws ExceptionInterface
     */
    private function createFileObject(string $path): File
    {
        return
            (new File())
                ->setPath($path)
                ->setStorageClass(self::class);
    }

    public function refreshFileInfo(FileInterface $file): FileInterface
    {
        if (false === $this->fileExists($file))
        {
            ExceptionHelper::create(new FileNotFoundException())
                ->message('File at path [%s] does not exist on storage [%s]', $file->getPath(), $file->getStorageClass())
                ->throw()
            ;
        }
        $filePath = $this->getFullFilePath($file);
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
     * @param FileInterface $file
     * @return string|null
     */
    private function detectFileMimeType(FileInterface $file): ?string
    {
        $fullFilePath = $this->getFullFilePath($file);
        return \mime_content_type($fullFilePath) ?? null;
    }
}