<?php

namespace Takeoto\Type\Contract\Type;

interface MixedTypeInterface
{
    /**
     * @return mixed
     * @throws \Throwable
     */
    public function mixed(): mixed;
}