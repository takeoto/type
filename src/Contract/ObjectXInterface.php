<?php

declare(strict_types=1);

namespace Takeoto\Type\Contract;

interface ObjectXInterface
{
    /**
     * @param string $name
     * @param mixed[] $arguments
     * @return MixedXInterface
     */
    public function __call(string $name, array $arguments): MixedXInterface;

    /**
     * @param string $key
     * @return MixedXInterface
     */
    public function __get(string $key): MixedXInterface;

    /**
     * @template TInstance of object
     * @param class-string<TInstance> $class
     * @return TInstance
     */
    public function instanceOf(string $class): object;

    /**
     * @return object
     */
    public function object(): object;
}