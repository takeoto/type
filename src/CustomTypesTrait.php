<?php

namespace Takeoto\Type;

use Takeoto\Type\Type\ArrayX;
use Takeoto\Type\Utility\CallUtility;
use Takeoto\Type\Utility\TypeUtility;

/**
 * @method static null|int nullOrInt(mixed $value, ?string $errorMessage = null)
 * @method static null|float nullOrFloat(mixed $value, ?string $errorMessage = null)
 * @method static null|string nullOrString(mixed $value, ?string $errorMessage = null)
 * @method static null|object nullOrObject(mixed $value, ?string $errorMessage = null)
 * @method static null|array nullOrArray(mixed $value, ?string $errorMessage = null)
 * @method static null|bool nullOrBool(mixed $value, ?string $errorMessage = null)
 * @method static null|callable nullOrCallable(mixed $value, ?string $errorMessage = null)
 * @method static ArrayX arrayXGet(mixed[] $array, string $key)
 * @method static int arrayXGetInt(mixed[] $array, string $key)
 * @method static float arrayXGetFloat(mixed[] $array, string $key)
 * @method static string arrayXGetString(mixed[] $array, string $key)
 * @method static object arrayXGetObject(mixed[] $array, string $key)
 * @method static array arrayXGetArray(mixed[] $array, string $key)
 * @method static bool arrayXGetBool(mixed[] $array, string $key)
 * @method static callable arrayXGetCallable(mixed[] $array, string $key)
 * @method static mixed arrayXGetMixed(mixed[] $array, string $key)
 * @method static null arrayXGetNull(mixed[] $array, string $key)
 * @method static null|int arrayXGetNullOrInt(mixed[] $array, string $key)
 * @method static null|float arrayXGetNullOrFloat(mixed[] $array, string $key)
 * @method static null|string arrayXGetNullOrString(mixed[] $array, string $key)
 * @method static null|object arrayXGetNullOrObject(mixed[] $array, string $key)
 * @method static null|array arrayXGetNullOrArray(mixed[] $array, string $key)
 * @method static null|bool arrayXGetNullOrBool(mixed[] $array, string $key)
 * @method static null|callable arrayXGetNullOrCallable(mixed[] $array, string $key)
 * @method static int arrayXGetErrorIfNotInt(mixed[] $array, string $key, ?string $errorMessage)
 * @method static float arrayXGetErrorIfNotFloat(mixed[] $array, string $key, ?string $errorMessage)
 * @method static string arrayXGetErrorIfNotString(mixed[] $array, string $key, ?string $errorMessage)
 * @method static object arrayXGetErrorIfNotObject(mixed[] $array, string $key, ?string $errorMessage)
 * @method static array arrayXGetErrorIfNotArray(mixed[] $array, string $key, ?string $errorMessage)
 * @method static bool arrayXGetErrorIfNotBool(mixed[] $array, string $key, ?string $errorMessage)
 * @method static callable arrayXGetErrorIfNotCallable(mixed[] $array, string $key, ?string $errorMessage)
 * @method static mixed arrayXGetErrorIfNotMixed(mixed[] $array, string $key, ?string $errorMessage)
 * @method static null arrayXGetErrorIfNotNull(mixed[] $array, string $key, ?string $errorMessage)
 * @method static null|int arrayXGetErrorIfNotNullOrInt(mixed[] $array, string $key, ?string $errorMessage)
 * @method static null|float arrayXGetErrorIfNotNullOrFloat(mixed[] $array, string $key, ?string $errorMessage)
 * @method static null|string arrayXGetErrorIfNotNullOrString(mixed[] $array, string $key, ?string $errorMessage)
 * @method static null|object arrayXGetErrorIfNotNullOrObject(mixed[] $array, string $key, ?string $errorMessage)
 * @method static null|array arrayXGetErrorIfNotNullOrArray(mixed[] $array, string $key, ?string $errorMessage)
 * @method static null|bool arrayXGetErrorIfNotNullOrBool(mixed[] $array, string $key, ?string $errorMessage)
 */
trait CustomTypesTrait
{
    /**
     * @param mixed $value
     * @param string|null $message
     * @return string
     * @throws \Throwable
     */
    public static function stringInt(mixed $value, string $message = null): string
    {
        TypeUtility::ensure($value, TypeUtility::TYPE_STRING_INT, $message ?? 'Expected an int as a string. Got: %s');

        /** @var string $value */
        return $value;
    }

    /**
     * @param string $method
     * @param mixed[] $arguments
     * @return mixed
     * @throws \Throwable
     */
    public static function __callStatic(string $method, array $arguments): mixed
    {
        return CallUtility::isStrictTypeCall($method)
            ? CallUtility::strictTypeCall($method, $arguments)
            : CallUtility::callChain($method, $arguments, static::class);
    }
}