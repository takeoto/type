<?php

declare(strict_types=1);

namespace Takeoto\Type;

use Takeoto\Type\Contract\ArrayXInterface;
use Takeoto\Type\Contract\MixedXInterface;
use Takeoto\Type\Contract\ObjectXInterface;
use Takeoto\Type\Type\ArrayX;
use Takeoto\Type\Type\MixedX;
use Takeoto\Type\Type\ObjectX;
use Takeoto\Type\Utility\TypeUtility;

/**
 * @method static int arrayXGetInt(mixed[] $array, string $key)
 * @method static int arrayXGetFloat(mixed[] $array, string $key)
 * @method static int arrayXGetString(mixed[] $array, string $key)
 * @method static int arrayXGetObject(mixed[] $array, string $key)
 * @method static int arrayXGetArray(mixed[] $array, string $key)
 * @method static int arrayXGetBool(mixed[] $array, string $key)
 * @method static int arrayXGetMixed(mixed[] $array, string $key)
 * @method static int arrayXGetNull(mixed[] $array, string $key)
 * @method static int arrayXGetErrorIfNotInt(mixed[] $array, string $key, ?string $errorMessage)
 * @method static int arrayXGetErrorIfNotFloat(mixed[] $array, string $key, ?string $errorMessage)
 * @method static int arrayXGetErrorIfNotString(mixed[] $array, string $key, ?string $errorMessage)
 * @method static int arrayXGetErrorIfNotObject(mixed[] $array, string $key, ?string $errorMessage)
 * @method static int arrayXGetErrorIfNotArray(mixed[] $array, string $key, ?string $errorMessage)
 * @method static int arrayXGetErrorIfNotBool(mixed[] $array, string $key, ?string $errorMessage)
 * @method static int arrayXGetErrorIfNotMixed(mixed[] $array, string $key, ?string $errorMessage)
 * @method static int arrayXGetErrorIfNotNull(mixed[] $array, string $key, ?string $errorMessage)
 */
class Type
{
    /**
     * @param mixed $value
     * @param string|null $message
     * @return int
     * @throws \Throwable
     */
    public static function int(mixed $value, string $message = null): int
    {
        TypeUtility::ensureType($value, TypeUtility::TYPE_INT, $message ?? 'Expected an integer. Got: %s');

        return (int)$value;
    }

    /**
     * @param mixed $value
     * @param string|null $message
     * @return float
     * @throws \Throwable
     */
    public static function float(mixed $value, string $message = null): float
    {
        TypeUtility::ensureType($value, TypeUtility::TYPE_FLOAT, $message ?? 'Expected a float. Got: %s');

        return (float)$value;
    }

    /**
     * @param mixed $value
     * @param string|null $message
     * @return string
     * @throws \Throwable
     */
    public static function string(mixed $value, string $message = null): string
    {
        TypeUtility::ensureType($value, TypeUtility::TYPE_STRING, $message ?? 'Expected a string. Got: %s');

        return (string)$value;
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
        TypeUtility::ensureType($value, TypeUtility::TYPE_OBJECT, $message ?? 'Expected an object. Got: %s');

        return (object)$value;
    }

    /**
     * @param mixed $value
     * @param string|null $message
     * @return mixed[]
     * @throws \Throwable
     */
    public static function array(mixed $value, string $message = null): array
    {
        TypeUtility::ensureType($value, TypeUtility::TYPE_ARRAY, $message ?? 'Expected an array. Got: %s');

        return (array)$value;
    }

    /**
     * @param mixed $value
     * @param string|null $message
     * @return bool
     * @throws \Throwable
     */
    public static function bool(mixed $value, string $message = null): bool
    {
        TypeUtility::ensureType($value, TypeUtility::TYPE_BOOL, $message ?? 'Expected a boolean. Got: %s');

        return (bool)$value;
    }

    /**
     * @param mixed $value
     * @param string|null $message
     * @return null
     * @throws \Throwable
     */
    public static function null(mixed $value, string $message = null)
    {
        TypeUtility::ensureType($value, TypeUtility::TYPE_NULL, $message ?? 'Expected a null. Got: %s');

        return null;
    }

    /**
     * @param mixed $value
     * @return MixedXInterface
     */
    public static function mixedX(mixed $value): MixedXInterface
    {
        return MixedX::new($value);
    }

    /**
     * @param mixed $value
     * @param string|null $message
     * @return ArrayXInterface<int|string,mixed>
     * @throws \Throwable
     */
    public static function arrayX(mixed $value, string $message = null): ArrayXInterface
    {
        return ArrayX::new($value, $message);
    }

    /**
     * @param mixed $value
     * @param string|null $message
     * @return ObjectXInterface
     * @throws \Throwable
     */
    public static function objectX(mixed $value, string $message = null): ObjectXInterface
    {
        return ObjectX::new($value, $message);
    }

    /**
     * @throws \Throwable
     * @return never-return
     */
    protected static function throwWrongTypeException(string $message): void
    {
        TypeUtility::throwWrongTypeException($message);
    }

    /**
     * @param string $method
     * @param mixed[] $arguments
     * @return mixed
     * @throws \Throwable
     */
    public static function __callStatic(string $method, array $arguments): mixed
    {
        return TypeUtility::callChain($method, $arguments, self::class);
    }

    private function __construct()
    {
    }
}