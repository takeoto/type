<?php

declare(strict_types=1);

namespace Takeoto\Type;

use Takeoto\Type\Contract\MagicStaticCallableInterface;
use Takeoto\Type\Contract\TransitionalInterface;
use Takeoto\Type\Dictionary\SchemeDict;
use Takeoto\Type\Dictionary\TypeDict;
use Takeoto\Type\Type\ArrayX;
use Takeoto\Type\Type\MixedX;
use Takeoto\Type\Type\ObjectX;
use Takeoto\Type\Utility\CallUtility;
use Takeoto\Type\Utility\TypeUtility;

class Type implements MagicStaticCallableInterface, TransitionalInterface
{
    use PseudoTypesTrait;
    use CustomTypesTrait;

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
     * @return mixed[]
     */
    public static function arrayXScheme(): array
    {
        return [
            SchemeDict::ARGUMENTS => [
                [
                    SchemeDict::TYPE => TypeDict::MIXED,
                ],
                [
                    SchemeDict::TYPE => TypeUtility::oneOf(TypeDict::STRING, TypeDict::NULL),
                    SchemeDict::DEFAULT => null,
                ],
            ],
            SchemeDict::RETURN => ArrayX::class,
        ];
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
     * @return mixed[]
     */
    public static function objectXScheme(): array
    {
        return [
            SchemeDict::ARGUMENTS => [
                [
                    SchemeDict::TYPE => TypeDict::MIXED,
                ],
                [
                    SchemeDict::TYPE => TypeUtility::oneOf(TypeDict::STRING, TypeDict::NULL),
                    SchemeDict::DEFAULT => null,
                ],
            ],
            SchemeDict::RETURN => ObjectX::class,
        ];
    }

    private function __construct()
    {
    }
}