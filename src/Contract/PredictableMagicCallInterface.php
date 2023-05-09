<?php

namespace Takeoto\Type\Contract;

interface PredictableMagicCallInterface
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
     */
    public function __call(string $method, array $arguments): mixed;
}