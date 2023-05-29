<?php

declare(strict_types=1);

namespace Takeoto\Type\Contract\Type;

interface NullTypeInterface
{
    /**
     * @return null
     * @throws \Throwable
     */
    public function null();
}