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
     * @param string|null $message
     * @return iterable
     * @throws \Throwable
     */
    public static function iterable(mixed $value, string $message = null): iterable
    {
        TypeUtility::ensure($value, TypeUtility::TYPE_ITERABLE, $message ?? 'Expected an iterable. Got: %s');

        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $message
     * @return string|int|float
     * @throws \Throwable
     */
    public static function numeric(mixed $value, string $message = null): string|int|float
    {
        TypeUtility::ensure($value, TypeUtility::TYPE_NUMERIC, $message ?? 'Expected a numeric. Got: %s');

        return $value;
    }
}