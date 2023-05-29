<?php

declare(strict_types=1);

namespace Takeoto\Type\Contract\Scheme;

interface MethodSchemeInterface
{
    public function getName(): string;

    /**
     * @return MethodArgumentSchemeInterface[]
     */
    public function getArguments(): array;

    /**
     * @return string
     */
    public function getReturnType(): string;
}