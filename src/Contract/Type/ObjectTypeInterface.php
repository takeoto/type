<?php

namespace Takeoto\Type\Contract\Type;

interface ObjectTypeInterface
{
    /**
     * @return object
     * @throws \Throwable
     */
    public function object(): object;
}