<?php

declare(strict_types=1);

namespace Takeoto\Type\Contract\Type;

interface StringTypeInterface
{
    /**
     * @return string
     * @throws \Throwable
     */
    public function string(): string;
}