<?php

namespace Takeoto\Type\Contract\Type;

interface NullTypeInterface
{
    /**
     * @return null
     * @throws \Throwable
     */
    public function null();
}