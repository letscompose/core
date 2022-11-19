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
use LetsCompose\Core\Exception\ExceptionInterface;

class ExceptionHelper
{
    /**
     * @var string
     */
    private const CLOSURE_SET_MESSAGE  = 'message';

    /**
     * @var string
     */
    private const CLOSURE_SET_CODE  = 'code';

    /**
     * @var string
     */
    private const CLOSURE_SET_PREVIOUS  = 'previous';

    /**
     * @var Closure[]
     */
    private array $closureMap = [];

    /**
     * @param ExceptionInterface $exception
     */
    public function __construct
    (
        private readonly ExceptionInterface $exception
    ) {
        $this->closureMap = [
            self::CLOSURE_SET_MESSAGE => fn (string $message, ...$params) => $this->message = sprintf($message, ...$params),
            self::CLOSURE_SET_CODE => (fn (int $code) => $this->code = $code),
            self::CLOSURE_SET_PREVIOUS => (fn (\Throwable $previous) => $this->previous = $previous),
        ];

        $test = function (string $message, ...$params) {
            $this->message = sprintf($message, ...$params);
        };

        foreach ($this->closureMap as &$closure)
        {
            $closure = $closure->bindTo($this->exception, $this->exception);
        }
    }

    /**
     * @param ExceptionInterface $exception
     * @return ExceptionHelper
     */
    public static function create(ExceptionInterface $exception): self
    {
        return new self($exception);
    }

    /**
     * @param string $message
     * @param ...$params
     * @return ExceptionHelper
     */
    public function message(string $message, ...$params): self {
        $this->closureMap[__FUNCTION__]($message, ...$params);
        return $this;
    }

    /**
     * @param int $code
     * @return ExceptionHelper
     */
    public function code(int $code): self {
        $this->closureMap[__FUNCTION__]($code);
        return $this;
    }

    /**
     * @param \Throwable $exception
     * @return ExceptionHelper
     */
    public function previous(\Throwable $exception): self {
        $this->closureMap[__FUNCTION__]($exception);
        return $this;
    }

    /**
     * @return never
     * @throws Exception|ExceptionInterface
     */
    public function throw(): never {
        throw $this->exception;
    }
}