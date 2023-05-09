<?php

declare(strict_types=1);

namespace Takeoto\Type;

use Takeoto\Type\Type\ArrayX;
use Takeoto\Type\Type\MixedX;
use Takeoto\Type\Type\ObjectX;
use Takeoto\Type\Utility\CallUtility;
use Takeoto\Type\Utility\TypeUtility;

/**
 * @method static null|int nullOrInt(mixed $value, ?string $errorMessage = null)
 * @method static null|float nullOrFloat(mixed $value, ?string $errorMessage = null)
 * @method static null|string nullOrString(mixed $value, ?string $errorMessage = null)
 * @method static null|object nullOrObject(mixed $value, ?string $errorMessage = null)
 * @method static null|array nullOrArray(mixed $value, ?string $errorMessage = null)
 * @method static null|bool nullOrBool(mixed $value, ?string $errorMessage = null)
 * @method static ArrayX arrayXGet(mixed[] $array, string $key)
 * @method static int arrayXGetInt(mixed[] $array, string $key)
 * @method static float arrayXGetFloat(mixed[] $array, string $key)
 * @method static string arrayXGetString(mixed[] $array, string $key)
 * @method static object arrayXGetObject(mixed[] $array, string $key)
 * @method static array arrayXGetArray(mixed[] $array, string $key)
 * @method static bool arrayXGetBool(mixed[] $array, string $key)
 * @method static mixed arrayXGetMixed(mixed[] $array, string $key)
 * @method static null arrayXGetNull(mixed[] $array, string $key)
 * @method static null|int arrayXGetNullOrInt(mixed[] $array, string $key)
 * @method static null|float arrayXGetNullOrFloat(mixed[] $array, string $key)
 * @method static null|string arrayXGetNullOrString(mixed[] $array, string $key)
 * @method static null|object arrayXGetNullOrObject(mixed[] $array, string $key)
 * @method static null|array arrayXGetNullOrArray(mixed[] $array, string $key)
 * @method static null|bool arrayXGetNullOrBool(mixed[] $array, string $key)
 * @method static int arrayXGetErrorIfNotInt(mixed[] $array, string $key, ?string $errorMessage)
 * @method static float arrayXGetErrorIfNotFloat(mixed[] $array, string $key, ?string $errorMessage)
 * @method static string arrayXGetErrorIfNotString(mixed[] $array, string $key, ?string $errorMessage)
 * @method static object arrayXGetErrorIfNotObject(mixed[] $array, string $key, ?string $errorMessage)
 * @method static array arrayXGetErrorIfNotArray(mixed[] $array, string $key, ?string $errorMessage)
 * @method static bool arrayXGetErrorIfNotBool(mixed[] $array, string $key, ?string $errorMessage)
 * @method static mixed arrayXGetErrorIfNotMixed(mixed[] $array, string $key, ?string $errorMessage)
 * @method static null arrayXGetErrorIfNotNull(mixed[] $array, string $key, ?string $errorMessage)
 * @method static null|int arrayXGetErrorIfNotNullOrInt(mixed[] $array, string $key, ?string $errorMessage)
 * @method static null|float arrayXGetErrorIfNotNullOrFloat(mixed[] $array, string $key, ?string $errorMessage)
 * @method static null|string arrayXGetErrorIfNotNullOrString(mixed[] $array, string $key, ?string $errorMessage)
 * @method static null|object arrayXGetErrorIfNotNullOrObject(mixed[] $array, string $key, ?string $errorMessage)
 * @method static null|array arrayXGetErrorIfNotNullOrArray(mixed[] $array, string $key, ?string $errorMessage)
 * @method static null|bool arrayXGetErrorIfNotNullOrBool(mixed[] $array, string $key, ?string $errorMessage)
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
        return CallUtility::isStrictTypeCall($method, $arguments)
            ? CallUtility::strictTypeCall($method, $arguments)
            : CallUtility::callChain($method, $arguments, static::class);
    }

    private function __construct()
    {
    }
}