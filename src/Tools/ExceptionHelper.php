<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Tools;

use Exception;

class ExceptionHelper
{
    /**
     * @var string
     */
    private const CLOSURE_SET_MESSAGE  = 'setMessage';

    /**
     * @var string
     */
    private const CLOSURE_SET_CODE  = 'setCode';

    /**
     * @var string
     */
    private const CLOSURE_SET_PREVIOUS  = 'setPrevious';

    /**
     * @var Closure[]
     */
    private array $closureMap = [];

    /**
     * @param Exception $exception
     */
    public function __construct
    (
        private readonly Exception $exception
    ) {
        $this->closureMap = [
            self::CLOSURE_SET_MESSAGE => fn (string $message, ...$params) => $this->message = sprintf($message, ...$params),
            self::CLOSURE_SET_CODE => (fn (int $code) => $this->code = $code),
            self::CLOSURE_SET_PREVIOUS => (fn (\Throwable $previous) => $this->previous = $previous),
        ];

        foreach ($this->closureMap as $method => &$closure)
        {
            if (false === method_exists($exception, $method))
            {
                $closure = $closure->bindTo($exception, $exception);
            } else {
                $closure = fn (...$params) => $this->exception->{$method}(...$params);
            }
        }
    }

    /**
     * @param Exception $exception
     * @return ExceptionHelper
     */
    public static function create(Exception $exception): self
    {
        return new self($exception);
    }

    /**
     * @param string $message
     * @param ...$params
     * @return ExceptionHelper
     */
    public function setMessage(string $message, ...$params): self {
        $this->closureMap[__FUNCTION__]($message, ...$params);
        return $this;
    }

    /**
     * @param int $code
     * @return ExceptionHelper
     */
    public function setCode(int $code): self {
        $this->closureMap[__FUNCTION__]($code);
        return $this;
    }

    /**
     * @param \Throwable $exception
     * @return ExceptionHelper
     */
    public function setPrevious(\Throwable $exception): self {
        $this->closureMap[__FUNCTION__]($exception);
        return $this;
    }

    public function get(): \Exception
    {
        return $this->exception;
    }

    /**
     * @return never
     * @throws Exception
     */
    public function throw(): never {
        throw $this->get();
    }
}