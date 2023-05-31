<?php

namespace Takeoto\Type;

use Takeoto\Type\Contract\Scheme\MethodSchemeInterface;
use Takeoto\Type\Scheme\MethodScheme;
use Takeoto\Type\Type\ArrayX;
use Takeoto\Type\Type\IntX;
use Takeoto\Type\Type\MixedX;
use Takeoto\Type\Type\ObjectX;
use Takeoto\Type\Type\StringX;

/**
 * ArrayX a strict value getting
 *
 * @method static MixedX arrayXGet(mixed[] $array, string $key)
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
trait XTypesTrait
{
    use DynamicTypesTrait;

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
     * @return StringX
     * @throws \Throwable
     */
    public static function stringX(mixed $value, string $error = null): StringX
    {
        return StringX::new($value, $error);
    }

    /**
     * @param mixed $value
     * @param string|null $error
     * @return IntX
     * @throws \Throwable
     */
    public static function intX(mixed $value, string $error = null): IntX
    {
        return IntX::new($value, $error);
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
     * The scheme for self::arrayX
     *
     * @return MethodSchemeInterface
     */
    public static function arrayXScheme(): MethodSchemeInterface
    {
        return MethodScheme::new('arrayX')
            ->arg(0, 'mixed')
            ->arg(1, 'string|null')->default(null)
            ->return(ArrayX::class);
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

    /**
     * The scheme for self::objectX
     *
     * @return MethodSchemeInterface
     */
    public static function objectXScheme(): MethodSchemeInterface
    {
        return MethodScheme::new('objectX')
            ->arg(0, 'mixed')
            ->arg(1, 'string|null')->default(null)
            ->return(ObjectX::class);
    }
}