<?php

namespace Takeoto\Type\Contract\Type;

interface FloatTypeInterface
{
    /**
     * @return float
     * @throws \Throwable
     */
    public function float(): float;
}