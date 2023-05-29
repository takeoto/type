<?php

declare(strict_types=1);

namespace Takeoto\Type\Contract;

use Takeoto\Type\Contract\Scheme\MethodSchemeInterface;

interface TransitionalInterface
{
    public static function getMethodScheme(string $method): ?MethodSchemeInterface;
}