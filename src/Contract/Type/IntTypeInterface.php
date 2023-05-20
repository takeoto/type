<?php

namespace Takeoto\Type\Contract\Type;

interface IntTypeInterface
{
    /**
     * @return int
     * @throws \Throwable
     */
    public function int(): int;
}