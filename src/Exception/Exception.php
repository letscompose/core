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
use Throwable;


/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
class Exception extends PHPException implements ExceptionInterface
{
    /**
     * @var Throwable
     */
    private Throwable $previous;

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
    }

}