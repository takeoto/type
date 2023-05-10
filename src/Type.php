<?php

declare(strict_types=1);

namespace Takeoto\Type;

use Takeoto\Type\Type\ArrayX;
use Takeoto\Type\Type\MixedX;
use Takeoto\Type\Type\ObjectX;
use Takeoto\Type\Utility\TypeUtility;

class Type
{
    use PseudoTypesTrait;
    use CustomTypesTrait;

    /**
     * @param mixed $value
     * @param string|null $message
     * @return int
     * @throws \Throwable
     */
    public static function int(mixed $value, string $message = null): int
    {
        TypeUtility::ensure($value, TypeUtility::TYPE_INT, $message ?? 'Expected an integer. Got: %s');

        /** @var int $value */
        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $message
     * @return float
     * @throws \Throwable
     */
    public static function float(mixed $value, string $message = null): float
    {
        TypeUtility::ensure($value, TypeUtility::TYPE_FLOAT, $message ?? 'Expected a float. Got: %s');

        /** @var float $value */
        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $message
     * @return string
     * @throws \Throwable
     */
    public static function string(mixed $value, string $message = null): string
    {
        TypeUtility::ensure($value, TypeUtility::TYPE_STRING, $message ?? 'Expected a string. Got: %s');

        /** @var string $value */
        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $message
     * @return object
     * @phpstan-assert object $value
     * @throws \Throwable
     */
    public static function object(mixed $value, string $message = null): object
    {
        TypeUtility::ensure($value, TypeUtility::TYPE_OBJECT, $message ?? 'Expected an object. Got: %s');

        /** @var object $value */
        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $message
     * @return mixed[]
     * @throws \Throwable
     */
    public static function array(mixed $value, string $message = null): array
    {
        TypeUtility::ensure($value, TypeUtility::TYPE_ARRAY, $message ?? 'Expected an array. Got: %s');

        /** @var mixed[] $value */
        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $message
     * @return bool
     * @throws \Throwable
     */
    public static function bool(mixed $value, string $message = null): bool
    {
        TypeUtility::ensure($value, TypeUtility::TYPE_BOOL, $message ?? 'Expected a boolean. Got: %s');

        /** @var bool $value */
        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $message
     * @return callable
     * @throws \Throwable
     */
    public static function callable(mixed $value, string $message = null): callable
    {
        TypeUtility::ensure($value, TypeUtility::TYPE_CALLABLE, $message ?? 'Expected a callable. Got: %s');

        /** @var callable $value */
        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $message
     * @return null
     * @throws \Throwable
     */
    public static function null(mixed $value, string $message = null)
    {
        TypeUtility::ensure($value, TypeUtility::TYPE_NULL, $message ?? 'Expected a null. Got: %s');

        return null;
    }

    /**
     * @param mixed $value
     * @return MixedX
     */
    public static function mixedX(mixed $value): MixedX
    {
        return MixedX::new($value);
    }

    /**
     * @param mixed $value
     * @param string|null $message
     * @return ArrayX<int|string,mixed>
     * @throws \Throwable
     */
    public static function arrayX(mixed $value, string $message = null): ArrayX
    {
        return ArrayX::new($value, $message);
    }

    /**
     * @param mixed $value
     * @param string|null $message
     * @return ObjectX
     * @throws \Throwable
     */
    public static function objectX(mixed $value, string $message = null): ObjectX
    {
        return ObjectX::new($value, $message);
    }

    private function __construct()
    {
    }
}