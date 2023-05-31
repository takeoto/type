<?php

declare(strict_types=1);

namespace Takeoto\Type;

use Takeoto\Type\Contract\Scheme\MethodSchemeInterface;
use Takeoto\Type\Dictionary\TypeDict;
use Takeoto\Type\Type\MixedX;
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
 */
trait CustomTypesTrait
{
    use DynamicTypesTrait;

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
}