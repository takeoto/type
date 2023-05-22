<?php

namespace Takeoto\Type\Contract\Scheme;

interface MethodSchemeInterface
{
    public function getName(): string;

    /**
     * @return MethodArgumentSchemeInterface[]
     */
    public function getArguments(): array;

    /**
     * @return string|string[]
     */
    public function getReturnType(): string|array;
}