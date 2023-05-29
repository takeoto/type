<?php

declare(strict_types=1);

namespace Takeoto\Type\Contract\Type;

interface MixedTypeInterface
{
    /**
     * @return mixed
     * @throws \Throwable
     */
    public function mixed(): mixed;
}