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
    const TYPE_FILE = 'file';

    const TYPE_DIRECTORY = 'directory';

    const STATE_OPENED_STREAM = 'stream-opened';

    const STATE_CLOSED_STREAM = 'stream-closed';

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

    public function setStorageClass(string $class): ResourceInterface;

    public function getStorageClass(): string;

    public function setName(string $name): ResourceInterface;

    public function getName(): string;

    public function setPath(string $path): ResourceInterface;

    public function getPath(): string;

    public function setStream(mixed $stream): ResourceInterface;

    public function getStream(): mixed;

    public function setType(string $type): ResourceInterface;

    public function getType(): string;

    public function setState(string $state): ResourceInterface;

    public function getState(): string;

    public function isOpen(): bool;
}