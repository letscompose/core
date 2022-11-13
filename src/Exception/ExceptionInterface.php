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

use Throwable;

interface ExceptionInterface extends Throwable {
    /**
     * @param string $message
     * @return mixed
     */
    public function setMessage(string $message): ExceptionInterface;

    /**
     * @param int $code
     * @return mixed
     */
    public function setCode(int $code): ExceptionInterface;

    /**
     * @param Throwable $exception
     * @return mixed
     */
    public function setPrevious(Throwable $exception): ExceptionInterface;
}