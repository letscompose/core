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

use Symfony\Component\ExpressionLanguage\ExpressionFunction;

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
     * @param \Exception $exception
     */
    public function __construct
    (
        private \Exception $exception
    ) {
        $this->closureMap = [
            self::CLOSURE_SET_MESSAGE => fn (string $message, ...$params) => $this->message = sprintf($message, ...$params),
            self::CLOSURE_SET_CODE => (fn (int $code) => $this->code = $code),
            self::CLOSURE_SET_PREVIOUS => (fn (\Throwable $previous) => $this->previous = $previous),
        ];

        foreach ($this->closureMap as &$closure)
        {
            $closure = $closure->bindTo($this->exception, $this->exception);
        }
    }

    /**
     * @param string $exception
     * @return ExceptionHelper
     */
    public static function create($exception)
    {

//        if (!\class_exists($exception))
//        {
//            throw new \InvalidArgumentException('passed argument must be an valid existing class name or \Exception object');
//        }

//        $exception = eval("return (new class() extends $exception {});");

        if (false === $exception instanceof \Exception)
        {
            throw new \InvalidArgumentException('argument must be an valid instance of \Exception object');
        }

        return new self($exception);
    }

    /**
     * @param string $message
     * @param ...$params
     * @return $this
     */
    public function message(string $message, ...$params): self {
        $this->closureMap[__FUNCTION__]($message, ...$params);
        return $this;
    }

    /**
     * @param int $code
     * @return $this
     */
    public function code(int $code): self {
        $this->closureMap[__FUNCTION__]($code);
        return $this;
    }

    /**
     * @param \Throwable $exception
     * @return $this
     */
    public function previous(\Throwable $exception): self {
        $this->closureMap[__FUNCTION__]($exception);
        return $this;
    }

    /**
     * @return never
     * @throws \Exception
     */
    public function throw(): never {
        throw $this->exception;
    }
}