<?php

namespace Takeoto\Type\Contract;

interface TransitionalInterface
{
    public static function parseTransitMethod(string $method): ?string;
    public static function getTransitMethodScheme(string $method): ?array;
}