<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Exception;

use \Exception as PHPException;
use LetsCompose\Core\Interface\PayloadInterface;
use Throwable;


/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class Exception extends PHPException implements ExceptionInterface, PayloadInterface
{
    /**
     * @var Throwable
     */
    private Throwable $previous;

    private mixed $payload = null;

    /**
     * @inheritDoc
     */
    public function setMessage(string $message, ...$params): ExceptionInterface
    {
        $this->message = sprintf($message, ...$params);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setCode(int $code): ExceptionInterface
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setPrevious(Throwable $exception): ExceptionInterface
    {
        $this->previous = $exception;
        return $this;
    }

    public function getPayload(): mixed
    {
        return $this->payload;
    }

    public function setPayload(mixed $payload): self
    {
        $this->payload = $payload;
        return $this;
    }
}