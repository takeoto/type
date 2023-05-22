<?php

namespace Takeoto\Type;

use Takeoto\Type\Contract\Scheme\MethodSchemeInterface;
use Takeoto\Type\Dictionary\TypeDict;
use Takeoto\Type\Type\ArrayX;
use Takeoto\Type\Utility\CallUtility;
use Takeoto\Type\Utility\TypeUtility;

/**
 * NOT types
 *
 * @method static mixed notEmpty(mixed $value, ?string $error = null)
 * @method static mixed notFalse(mixed $value, ?string $error = null)
 * @method static mixed notTrue(mixed $value, ?string $error = null)
 * @method static float|string|object|array|bool|callable|null|iterable notInt(mixed $value, ?string $error = null)
 * @method static int|string|object|array|bool|callable|null|iterable notFloat(mixed $value, ?string $error = null)
 * @method static int|float|object|array|bool|callable|null|iterable notString(mixed $value, ?string $error = null)
 * @method static int|float|string|array|bool|callable|null|iterable notObject(mixed $value, ?string $error = null)
 * @method static int|float|string|object|bool|callable|null|iterable notArray(mixed $value, ?string $error = null)
 * @method static int|float|string|object|array|callable|null|iterable notBool(mixed $value, ?string $error = null)
 * @method static int|float|string|object|array|bool|null|iterable notCallable(mixed $value, ?string $error = null)
 * @method static int|float|string|object|array|bool|callable|iterable notNull(mixed $value, ?string $error = null)
 *
 * Multiple types
 *
 * @method static null|int nullOrInt(mixed $value, ?string $error = null)
 * @method static null|float nullOrFloat(mixed $value, ?string $error = null)
 * @method static null|string nullOrString(mixed $value, ?string $error = null)
 * @method static null|object nullOrObject(mixed $value, ?string $error = null)
 * @method static null|array nullOrArray(mixed $value, ?string $error = null)
 * @method static null|bool nullOrBool(mixed $value, ?string $error = null)
 * @method static null|callable nullOrCallable(mixed $value, ?string $error = null)
 * @method static null|string nullOrStringInt(mixed $value, ?string $error = null)
 * @method static null|iterable nullOrIterable(mixed $value, ?string $error = null)
 * @method static null|string|int|float nullOrNumeric(mixed $value, ?string $error = null)
 * @method static int|string intOrStringInt(mixed $value, ?string $error = null)
 *
 * ArrayX a strict value getting
 *
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
 * @method static int|string arrayXGetIntOrStringInt(mixed[] $array, string $key)
 *
 * ArrayX a multiple type strict value getting
 *
 * @method static null|int arrayXGetNullOrInt(mixed[] $array, string $key)
 * @method static null|float arrayXGetNullOrFloat(mixed[] $array, string $key)
 * @method static null|string arrayXGetNullOrString(mixed[] $array, string $key)
 * @method static null|object arrayXGetNullOrObject(mixed[] $array, string $key)
 * @method static null|array arrayXGetNullOrArray(mixed[] $array, string $key)
 * @method static null|bool arrayXGetNullOrBool(mixed[] $array, string $key)
 * @method static null|callable arrayXGetNullOrCallable(mixed[] $array, string $key)
 * @method static null|int|string arrayXGetNullOrIntOrStringInt(mixed[] $array, string $key)
 *
 * ArrayX a strict value getting (with a custom error message)
 *
 * @method static int arrayXGetErrorIfNotInt(mixed[] $array, string $key, ?string $error = null)
 * @method static float arrayXGetErrorIfNotFloat(mixed[] $array, string $key, ?string $error = null)
 * @method static string arrayXGetErrorIfNotString(mixed[] $array, string $key, ?string $error = null)
 * @method static object arrayXGetErrorIfNotObject(mixed[] $array, string $key, ?string $error = null)
 * @method static array arrayXGetErrorIfNotArray(mixed[] $array, string $key, ?string $error = null)
 * @method static bool arrayXGetErrorIfNotBool(mixed[] $array, string $key, ?string $error = null)
 * @method static callable arrayXGetErrorIfNotCallable(mixed[] $array, string $key, ?string $error = null)
 * @method static null arrayXGetErrorIfNotNull(mixed[] $array, string $key, ?string $error = null)
 * @method static null arrayXGetErrorIfNotStringInt(mixed[] $array, string $key, ?string $error = null)
 *
 * ArrayX  a multiple type strict value getting (with a custom error message)
 *
 * @method static null|int arrayXGetErrorIfNotNullOrInt(mixed[] $array, string $key, ?string $error = null)
 * @method static null|float arrayXGetErrorIfNotNullOrFloat(mixed[] $array, string $key, ?string $error = null)
 * @method static null|string arrayXGetErrorIfNotNullOrString(mixed[] $array, string $key, ?string $error = null)
 * @method static null|object arrayXGetErrorIfNotNullOrObject(mixed[] $array, string $key, ?string $error = null)
 * @method static null|array arrayXGetErrorIfNotNullOrArray(mixed[] $array, string $key, ?string $error = null)
 * @method static null|bool arrayXGetErrorIfNotNullOrBool(mixed[] $array, string $key, ?string $error = null)
 * @method static null|Callable arrayXGetErrorIfNotNullOrCallable(mixed[] $array, string $key, ?string $error = null)
 * @method static null|string arrayXGetErrorIfNotNullOrStringInt(mixed[] $array, string $key, ?string $error = null)
 * @method static int|string arrayXGetErrorIfNotIntOrStringInt(mixed[] $array, string $key, ?string $error = null)
 * @method static null|int|string arrayXGetErrorIfNotNullOrIntOrStringInt(mixed[] $arr, string $k, ?string $err = null)
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
        TypeUtility::ensure($value, TypeDict::STRING_INT, $error);

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
        TypeUtility::ensure($value, TypeDict::EMPTY, $error);

        return $value;
    }

    /**
     * @inheritDoc
     */
    public static function supportMagicStaticCall(string $method): bool
    {
        return CallUtility::isStrictTypeCall($method) || CallUtility::isTransitCall($method, static::class);
    }

    /**
     * @inheritDoc
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

    /**
     * @inheritDoc
     */
    public static function parseTransitMethod(string $method): ?string
    {
        return CallUtility::parseMethod(
            $method,
            static::class,
            fn(string $method): bool => static::getTransitMethodScheme($method) !== null
        );
    }

    /**
     * @inheritDoc
     */
    public static function getTransitMethodScheme(string $method): ?MethodSchemeInterface
    {
        return CallUtility::getSelfMethodSchema($method, static::class);
    }
}