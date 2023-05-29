<?php

declare(strict_types=1);

namespace Takeoto\Type\Contract\Type;

interface BoolTypeInterface
{
    /**
     * @return bool
     * @throws \Throwable
     */
    public function bool(): bool;
}