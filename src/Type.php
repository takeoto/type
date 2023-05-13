<?php

declare(strict_types=1);

namespace Takeoto\Type;

use Takeoto\Type\Contract\MagicStaticCallableInterface;
use Takeoto\Type\Type\ArrayX;
use Takeoto\Type\Type\MixedX;
use Takeoto\Type\Type\ObjectX;
use Takeoto\Type\Utility\TypeUtility;

class Type implements MagicStaticCallableInterface
{
    use PseudoTypesTrait;
    use CustomTypesTrait;

    /**
     * @param mixed $value
     * @param string|null $error
     * @return int
     * @throws \Throwable
     */
    public static function int(mixed $value, string $error = null): int
    {
        TypeUtility::ensure($value, TypeUtility::TYPE_INT, $error ?? 'Expected an integer. Got: %s');

        /** @var int $value */
        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $error
     * @return float
     * @throws \Throwable
     */
    public static function float(mixed $value, string $error = null): float
    {
        TypeUtility::ensure($value, TypeUtility::TYPE_FLOAT, $error ?? 'Expected a float. Got: %s');

        /** @var float $value */
        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $error
     * @return string
     * @throws \Throwable
     */
    public static function string(mixed $value, string $error = null): string
    {
        TypeUtility::ensure($value, TypeUtility::TYPE_STRING, $error ?? 'Expected a string. Got: %s');

        /** @var string $value */
        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $error
     * @return object
     * @phpstan-assert object $value
     * @throws \Throwable
     */
    public static function object(mixed $value, string $error = null): object
    {
        TypeUtility::ensure($value, TypeUtility::TYPE_OBJECT, $error ?? 'Expected an object. Got: %s');

        /** @var object $value */
        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $error
     * @return mixed[]
     * @throws \Throwable
     */
    public static function array(mixed $value, string $error = null): array
    {
        TypeUtility::ensure($value, TypeUtility::TYPE_ARRAY, $error ?? 'Expected an array. Got: %s');

        /** @var mixed[] $value */
        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $error
     * @return bool
     * @throws \Throwable
     */
    public static function bool(mixed $value, string $error = null): bool
    {
        TypeUtility::ensure($value, TypeUtility::TYPE_BOOL, $error ?? 'Expected a boolean. Got: %s');

        /** @var bool $value */
        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $error
     * @return callable
     * @throws \Throwable
     */
    public static function callable(mixed $value, string $error = null): callable
    {
        TypeUtility::ensure($value, TypeUtility::TYPE_CALLABLE, $error ?? 'Expected a callable. Got: %s');

        /** @var callable $value */
        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $error
     * @return null
     * @throws \Throwable
     */
    public static function null(mixed $value, string $error = null)
    {
        TypeUtility::ensure($value, TypeUtility::TYPE_NULL, $error ?? 'Expected a null. Got: %s');

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
     * @param string|null $error
     * @return ArrayX<int|string,mixed>
     * @throws \Throwable
     */
    public static function arrayX(mixed $value, string $error = null): ArrayX
    {
        return ArrayX::new($value, $error);
    }

    /**
     * @param mixed $value
     * @param string|null $error
     * @return ObjectX
     * @throws \Throwable
     */
    public static function objectX(mixed $value, string $error = null): ObjectX
    {
        return ObjectX::new($value, $error);
    }

    private function __construct()
    {
    }
}