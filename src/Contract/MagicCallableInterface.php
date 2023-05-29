<?php

declare(strict_types=1);

namespace Takeoto\Type\Contract;

interface MagicCallableInterface
{
    /**
     * @param string $method
     * @return bool
     */
    public function supportMagicCall(string $method): bool;

    /**
     * @param string $method
     * @param mixed[] $arguments
     * @return mixed
     * @throws \Throwable
     */
    public function __call(string $method, array $arguments): mixed;
}