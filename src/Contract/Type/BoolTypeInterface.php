<?php

namespace Takeoto\Type\Contract\Type;

interface BoolTypeInterface
{
    /**
     * @return bool
     * @throws \Throwable
     */
    public function bool(): bool;
}