<?php

namespace Takeoto\Type\Contract;

interface MagicCallableInterface
{
    /**
     * @param string $method
     * @param mixed[] $arguments
     * @return bool
     */
    public function supportMagicCall(string $method, array $arguments): bool;

    /**
     * @param string $method
     * @param mixed[] $arguments
     * @return mixed
     * @throws \Throwable
     */
    public function __call(string $method, array $arguments): mixed;
}