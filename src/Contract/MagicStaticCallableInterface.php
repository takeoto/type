<?php

namespace Takeoto\Type\Contract;

interface MagicStaticCallableInterface
{
    /**
     * @param string $method
     * @return bool
     */
    public static function supportMagicStaticCall(string $method): bool;

    /**
     * @param string $method
     * @param mixed[] $arguments
     * @return mixed
     * @throws \Throwable
     */
    public static function __callStatic(string $method, array $arguments): mixed;
}