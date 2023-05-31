<?php

declare(strict_types=1);

namespace Takeoto\Type\Contract\Type;

interface MixedTypeInterface extends
    IntTypeInterface,
    FloatTypeInterface,
    StringTypeInterface,
    ObjectTypeInterface,
    ArrayTypeInterface,
    BoolTypeInterface,
    CallableTypeInterface,
    NullTypeInterface
{
    /**
     * @return mixed
     * @throws \Throwable
     */
    public function mixed(): mixed;
}