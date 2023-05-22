<?php

declare(strict_types=1);

namespace Takeoto\Type;

use Takeoto\Type\Contract\MagicStaticCallableInterface;
use Takeoto\Type\Contract\Scheme\MethodSchemeInterface;
use Takeoto\Type\Contract\TransitionalInterface;
use Takeoto\Type\Dictionary\MetaDict;
use Takeoto\Type\Dictionary\TypeDict;
use Takeoto\Type\Scheme\MethodScheme;
use Takeoto\Type\Type\ArrayX;
use Takeoto\Type\Type\MixedX;
use Takeoto\Type\Type\ObjectX;
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

    private function __construct()
    {
    }
}