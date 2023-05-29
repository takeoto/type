<?php

namespace Takeoto\Type\Contract\Scheme;

interface MethodArgumentSchemeInterface
{
    public function getName(): int|string;

    public function getType(): string;

    public function hasDefault(): bool;

    public function getDefault(): mixed;
}