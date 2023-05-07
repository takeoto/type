<?php

declare(strict_types=1);

namespace Takeoto\Strict\Contract;

# need improve [__call]
#/**
# * @template-covariant T of object
# * @mixin T
# */
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
     * @template T0 of object
     * @param class-string<T0> $class
     * @return T0
     */
    public function instanceOf(string $class): object;
}