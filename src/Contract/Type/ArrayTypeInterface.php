<?php

declare(strict_types=1);

namespace Takeoto\Type\Contract\Type;

interface ArrayTypeInterface
{
    /**
     * @return array<int|string,mixed>
     * @throws \Throwable
     */
    public function array(): array;
}