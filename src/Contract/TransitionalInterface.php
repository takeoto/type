<?php

namespace Takeoto\Type\Contract;

use Takeoto\Type\Contract\Scheme\MethodSchemeInterface;

interface TransitionalInterface
{
    public static function getMethodScheme(string $method): ?MethodSchemeInterface;
}