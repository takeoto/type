<?php

declare(strict_types=1);

namespace Takeoto\Type\Contract\TypeX;

use Takeoto\Type\Contract\Type\ObjectTypeInterface;

interface ObjectXTypeInterface extends ObjectTypeInterface
{
    /**
     * @param string $name
     * @param mixed[] $arguments
     * @return MixedXTypeInterface
     */
    public function __call(string $name, array $arguments): MixedXTypeInterface;

    /**
     * @param string $key
     * @return MixedXTypeInterface
     */
    public function __get(string $key): MixedXTypeInterface;

    /**
     * @template TInstance of object
     * @param class-string<TInstance> $class
     * @return TInstance
     */
    public function instanceOf(string $class): object;
}