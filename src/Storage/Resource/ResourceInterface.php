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
    const STATE_OPENED_STREAM = 'stream-opened';

    const STATE_CLOSED_STREAM = 'stream-closed';

    const STATE_MAP = [
        self::STATE_OPENED_STREAM,
        self::STATE_CLOSED_STREAM,
    ];


    public function setStorageClass(string $class): self;

    public function getStorageClass(): string;

    public function getResourceClass(string $type): string;

    public function setName(string $name): self;

    public function getName(): string;

    public function setPath(string $path): self;

    public function getPath(): string;

    public function setStream(mixed $stream): self;

    public function getStream(): mixed;

    public function setState(string $state): self;

    public function getState(): string;

    public function isOpen(): bool;
}