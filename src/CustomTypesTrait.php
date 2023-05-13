<?php

namespace Takeoto\Type;

use Takeoto\Type\Type\ArrayX;
use Takeoto\Type\Utility\CallUtility;
use Takeoto\Type\Utility\TypeUtility;

/**
 * @method static mixed notEmpty(mixed $value, ?string $error = null)
 * @method static float|string|object|array|bool|callable|null|iterable notInt(mixed $value, ?string $error = null)
 * @method static int|string|object|array|bool|callable|null|iterable notFloat(mixed $value, ?string $error = null)
 * @method static int|float|object|array|bool|callable|null|iterable notString(mixed $value, ?string $error = null)
 * @method static int|float|string|array|bool|callable|null|iterable notObject(mixed $value, ?string $error = null)
 * @method static int|float|string|object|bool|callable|null|iterable notArray(mixed $value, ?string $error = null)
 * @method static int|float|string|object|array|callable|null|iterable notBool(mixed $value, ?string $error = null)
 * @method static int|float|string|object|array|bool|null|iterable notCallable(mixed $value, ?string $error = null)
 * @method static int|float|string|object|array|bool|callable|iterable notNull(mixed $value, ?string $error = null)
 *
 * @method static null|int nullOrInt(mixed $value, ?string $error = null)
 * @method static null|float nullOrFloat(mixed $value, ?string $error = null)
 * @method static null|string nullOrString(mixed $value, ?string $error = null)
 * @method static null|object nullOrObject(mixed $value, ?string $error = null)
 * @method static null|array nullOrArray(mixed $value, ?string $error = null)
 * @method static null|bool nullOrBool(mixed $value, ?string $error = null)
 * @method static null|callable nullOrCallable(mixed $value, ?string $error = null)
 *
 * @method static ArrayX arrayXGet(mixed[] $array, string $key)
 *
 * @method static int arrayXGetInt(mixed[] $array, string $key)
 * @method static float arrayXGetFloat(mixed[] $array, string $key)
 * @method static string arrayXGetString(mixed[] $array, string $key)
 * @method static object arrayXGetObject(mixed[] $array, string $key)
 * @method static array arrayXGetArray(mixed[] $array, string $key)
 * @method static bool arrayXGetBool(mixed[] $array, string $key)
 * @method static callable arrayXGetCallable(mixed[] $array, string $key)
 * @method static mixed arrayXGetMixed(mixed[] $array, string $key)
 * @method static null arrayXGetNull(mixed[] $array, string $key)
 *
 * @method static null|int arrayXGetNullOrInt(mixed[] $array, string $key)
 * @method static null|float arrayXGetNullOrFloat(mixed[] $array, string $key)
 * @method static null|string arrayXGetNullOrString(mixed[] $array, string $key)
 * @method static null|object arrayXGetNullOrObject(mixed[] $array, string $key)
 * @method static null|array arrayXGetNullOrArray(mixed[] $array, string $key)
 * @method static null|bool arrayXGetNullOrBool(mixed[] $array, string $key)
 * @method static null|callable arrayXGetNullOrCallable(mixed[] $array, string $key)
 *
 * @method static int arrayXGetErrorIfNotInt(mixed[] $array, string $key, ?string $error = null)
 * @method static float arrayXGetErrorIfNotFloat(mixed[] $array, string $key, ?string $error = null)
 * @method static string arrayXGetErrorIfNotString(mixed[] $array, string $key, ?string $error = null)
 * @method static object arrayXGetErrorIfNotObject(mixed[] $array, string $key, ?string $error = null)
 * @method static array arrayXGetErrorIfNotArray(mixed[] $array, string $key, ?string $error = null)
 * @method static bool arrayXGetErrorIfNotBool(mixed[] $array, string $key, ?string $error = null)
 * @method static callable arrayXGetErrorIfNotCallable(mixed[] $array, string $key, ?string $error = null)
 * @method static null arrayXGetErrorIfNotNull(mixed[] $array, string $key, ?string $error = null)
 *
 * @method static null|int arrayXGetErrorIfNotNullOrInt(mixed[] $array, string $key, ?string $error = null)
 * @method static null|float arrayXGetErrorIfNotNullOrFloat(mixed[] $array, string $key, ?string $error = null)
 * @method static null|string arrayXGetErrorIfNotNullOrString(mixed[] $array, string $key, ?string $error = null)
 * @method static null|object arrayXGetErrorIfNotNullOrObject(mixed[] $array, string $key, ?string $error = null)
 * @method static null|array arrayXGetErrorIfNotNullOrArray(mixed[] $array, string $key, ?string $error = null)
 * @method static null|bool arrayXGetErrorIfNotNullOrBool(mixed[] $array, string $key, ?string $error = null)
 */
trait CustomTypesTrait
{
    /**
     * @param mixed $value
     * @param string|null $error
     * @return string
     * @throws \Throwable
     */
    public static function stringInt(mixed $value, string $error = null): string
    {
        TypeUtility::ensure($value, TypeUtility::TYPE_STRING_INT, $error ?? 'Expected an int as a string. Got: %s');

        /** @var string $value */
        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $error
     * @return mixed
     * @throws \Throwable
     */
    public static function empty(mixed $value, string $error = null): mixed
    {
        TypeUtility::ensure($value, TypeUtility::TYPE_EMPTY, $error ?? 'Expected an int as an empty. Got: %s');

        return $value;
    }

    public static function supportMagicStaticCall(string $method): bool
    {
        return CallUtility::isStrictTypeCall($method)
            || CallUtility::isTransitCall($method, self::class);
    }

    /**
     * @param string $method
     * @param mixed[] $arguments
     * @return mixed
     * @throws \Throwable
     */
    public static function __callStatic(string $method, array $arguments): mixed
    {
        if (!self::supportMagicStaticCall($method)) {
            throw new \RuntimeException(sprintf('Method "%s" does not exist.', $method));
        }

        if (CallUtility::isStrictTypeCall($method)) {
            return CallUtility::strictTypeCall($method, $arguments);
        }

        return CallUtility::callTransit($method, $arguments, self::class);
    }
}