<?php

namespace Takeoto\Type;

use Takeoto\Type\Utility\TypeUtility;

/**
 * @internal
 */
trait PseudoTypesTrait
{
    /**
     * @param mixed $value
     * @param string|null $error
     * @return iterable
     * @throws \Throwable
     */
    public static function iterable(mixed $value, string $error = null): iterable
    {
        TypeUtility::ensure($value, TypeUtility::TYPE_ITERABLE, $error ?? 'Expected an iterable. Got: %s');

        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $error
     * @return string|int|float
     * @throws \Throwable
     */
    public static function numeric(mixed $value, string $error = null): string|int|float
    {
        TypeUtility::ensure($value, TypeUtility::TYPE_NUMERIC, $error ?? 'Expected a numeric. Got: %s');

        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $error
     * @return bool
     * @throws \Throwable
     */
    public static function true(mixed $value, string $error = null): bool
    {
        TypeUtility::ensure($value, TypeUtility::TYPE_TRUE, $error ?? 'Expected a true. Got: %s');

        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $error
     * @return bool
     * @throws \Throwable
     */
    public static function false(mixed $value, string $error = null): bool
    {
        TypeUtility::ensure($value, TypeUtility::TYPE_FALSE, $error ?? 'Expected a false. Got: %s');

        return $value;
    }
}