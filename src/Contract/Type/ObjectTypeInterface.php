<?php

declare(strict_types=1);

namespace Takeoto\Type\Contract\Type;

interface ObjectTypeInterface
{
    /**
     * @return object
     * @throws \Throwable
     */
    public function object(): object;
}