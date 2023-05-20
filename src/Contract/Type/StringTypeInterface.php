<?php

namespace Takeoto\Type\Contract\Type;

interface StringTypeInterface
{
    /**
     * @return string
     * @throws \Throwable
     */
    public function string(): string;
}