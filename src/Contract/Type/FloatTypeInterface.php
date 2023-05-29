<?php

declare(strict_types=1);

namespace Takeoto\Type\Contract\Type;

interface FloatTypeInterface
{
    /**
     * @return float
     * @throws \Throwable
     */
    public function float(): float;
}