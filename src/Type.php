<?php

declare(strict_types=1);

namespace Takeoto\Type;

use Takeoto\Type\Contract\MagicStaticCallableInterface;
use Takeoto\Type\Contract\TransitionalInterface;
use Takeoto\Type\Dictionary\TypeDict;
use Takeoto\Type\Utility\TypeUtility;

class Type implements MagicStaticCallableInterface, TransitionalInterface
{
    use PseudoTypesTrait;
    use CustomTypesTrait;
    use XTypesTrait;

    /**
     * @param mixed $value
     * @param string|null $error
     * @return int
     * @throws \Throwable
     */
    public static function int(mixed $value, string $error = null): int
    {
        TypeUtility::ensure($value, TypeDict::INT, $error);

        /** @var int $value */
        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $error
     * @return float
     * @throws \Throwable
     */
    public static function float(mixed $value, string $error = null): float
    {
        TypeUtility::ensure($value, TypeDict::FLOAT, $error);

        /** @var float $value */
        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $error
     * @return string
     * @throws \Throwable
     */
    public static function string(mixed $value, string $error = null): string
    {
        TypeUtility::ensure($value, TypeDict::STRING, $error);

        /** @var string $value */
        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $error
     * @return object
     * @phpstan-assert object $value
     * @throws \Throwable
     */
    public static function object(mixed $value, string $error = null): object
    {
        TypeUtility::ensure($value, TypeDict::OBJECT, $error);

        /** @var object $value */
        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $error
     * @return mixed[]
     * @throws \Throwable
     */
    public static function array(mixed $value, string $error = null): array
    {
        TypeUtility::ensure($value, TypeDict::ARRAY, $error);

        /** @var mixed[] $value */
        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $error
     * @return bool
     * @throws \Throwable
     */
    public static function bool(mixed $value, string $error = null): bool
    {
        TypeUtility::ensure($value, TypeDict::BOOL, $error);

        /** @var bool $value */
        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $error
     * @return callable
     * @throws \Throwable
     */
    public static function callable(mixed $value, string $error = null): callable
    {
        TypeUtility::ensure($value, TypeDict::CALLABLE, $error);

        /** @var callable $value */
        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $error
     * @return null
     * @throws \Throwable
     */
    public static function null(mixed $value, string $error = null)
    {
        TypeUtility::ensure($value, TypeDict::NULL, $error);

        return null;
    }

    private function __construct()
    {
    }
}