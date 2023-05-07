<?php

declare(strict_types=1);

namespace Takeoto\Type\Contract;

use Takeoto\Type\Type;

class NullOr
{
    public static function int(mixed $value, string $message = null): null|int
    {
        return $value === null ? null : Type::int($value, $message ?? 'Expected a null|integer. Got: %s');
    }

    public static function float(mixed $value, string $message = null): null|float
    {
        return $value === null ? null : Type::float($value, $message ?? 'Expected a null|float. Got: %s');
    }

    public static function string(mixed $value, string $message = null): null|string
    {
        return $value === null ? null : Type::string($value, $message ?? 'Expected a null|string. Got: %s');
    }

    public static function object(mixed $value, string $message = null): null|object
    {
        return $value === null ? null : Type::object($value, $message ?? 'Expected a null|object. Got: %s');
    }

    /**
     * @param mixed $value
     * @param string|null $message
     * @return mixed[]|null
     * @throws \Throwable
     */
    public static function array(mixed $value, string $message = null): null|array
    {
        return $value === null ? null : Type::array($value, $message ?? 'Expected a null|array. Got: %s');
    }

    public static function bool(mixed $value, string $message = null): null|bool
    {
        return $value === null ? null : Type::bool($value, $message ?? 'Expected a null|boolean. Got: %s');
    }
}