<?php

namespace Takeoto\Type\Contract\Type;

interface CallableTypeInterface
{
    /**
     * @return callable
     * @throws \Throwable
     */
    public function callable(): callable;
}