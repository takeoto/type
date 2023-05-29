<?php

declare(strict_types=1);

namespace Takeoto\Type;

use Takeoto\Type\Dictionary\TypeDict;
use Takeoto\Type\Utility\TypeUtility;

/**
 * @internal
 */
trait PseudoTypesTrait
{
    /**
     * @param mixed $value
     * @param string|null $error
     * @return iterable<array-key,mixed>
     * @phpstan-assert iterable<array-key,mixed> $value
     * @throws \Throwable
     */
    public static function iterable(mixed $value, string $error = null): iterable
    {
        TypeUtility::ensure($value, TypeDict::ITERABLE, $error);

        /** @var iterable<array-key,mixed> $value */
        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $error
     * @return string|int|float
     * @phpstan-assert string|int|float $value
     * @throws \Throwable
     */
    public static function numeric(mixed $value, string $error = null): string|int|float
    {
        TypeUtility::ensure($value, TypeDict::NUMERIC, $error);

        /** @var string|int|float $value */
        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $error
     * @return bool
     * @phpstan-assert true $value
     * @throws \Throwable
     */
    public static function true(mixed $value, string $error = null): bool
    {
        TypeUtility::ensure($value, TypeDict::TRUE, $error);

        /** @var true $value */
        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $error
     * @return bool
     * @phpstan-assert false $value
     * @throws \Throwable
     */
    public static function false(mixed $value, string $error = null): bool
    {
        TypeUtility::ensure($value, TypeDict::FALSE, $error);

        /** @var false $value */
        return $value;
    }
}