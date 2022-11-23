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

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
interface ResourceInterface
{
    /**
     * @var string
     */
    const TYPE_FILE = 'file';

    /**
     * @var string
     */
    const TYPE_DIRECTORY = 'directory';

    /**
     * @var string
     */
    const STATE_OPENED_STREAM = 'stream-opened';

    /**
     * @var string
     */
    const STATE_CLOSED_STREAM = 'stream-closed';

    /**
     * @var string[]
     */
    const STATE_MAP = [
        self::STATE_OPENED_STREAM,
        self::STATE_CLOSED_STREAM,
    ];

    /**
     * @var string[]
     */
    const TYPE_MAP = [
        self::TYPE_FILE,
        self::TYPE_DIRECTORY,
    ];

    /**
     * @param string $class
     * @return ResourceInterface
     */
    public function setStorageClass(string $class): ResourceInterface;

    /**
     * @return string
     */
    public function getStorageClass(): string;

    /**
     * @param string $name
     * @return ResourceInterface
     */
    public function setName(string $name): ResourceInterface;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $path
     * @return ResourceInterface
     */
    public function setPath(string $path): ResourceInterface;

    /**
     * @return string
     */
    public function getPath(): string;

    /**
     * @param mixed $stream
     * @return ResourceInterface
     */
    public function setStream(mixed $stream): ResourceInterface;

    /**
     * @return mixed
     */
    public function getStream(): mixed;


    /**
     * @param string $type
     * @return ResourceInterface
     */
    public function setType(string $type): ResourceInterface;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param string $state
     * @return ResourceInterface
     */
    public function setState(string $state): ResourceInterface;

    /**
     * @return string
     */
    public function getState(): string;

    /**
     * @return bool
     */
    public function isOpen(): bool;
}