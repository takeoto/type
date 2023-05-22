<?php

namespace Takeoto\Type\Contract\Scheme;

interface MethodArgumentSchemeInterface
{
    public function getName(): int|string;

    /**
     * @return string|string[]
     */
    public function getType(): string|array;

    public function hasDefault(): bool;

    public function getDefault(): mixed;
}