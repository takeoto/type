<?php

namespace Takeoto\Type\Contract;

use Takeoto\Type\Contract\Scheme\MethodSchemeInterface;

interface TransitionalInterface
{
    public static function parseTransitMethod(string $method): ?string;
    public static function getTransitMethodScheme(string $method): ?MethodSchemeInterface;
}