<?php

declare(strict_types=1);

namespace Takeoto\Type;

use Takeoto\Type\Contract\ArrayXInterface;
use Takeoto\Type\Contract\MixedXInterface;
use Takeoto\Type\Contract\ObjectXInterface;
use Takeoto\Type\Exception\WrongTypeException;
use Takeoto\Type\Type\ArrayX;
use Takeoto\Type\Type\MixedX;
use Takeoto\Type\Type\ObjectX;

class Type
{
    /**
     * @param mixed $value
     * @param string|null $message
     * @return int
     * @throws \Throwable
     */
    public static function int(mixed $value, string $message = null): int
    {
        if (!\is_int($value)) {
            static::throwWrongTypeException(\sprintf(
                $message ?? 'Expected an integer. Got: %s',
                static::typeToString($value)
            ));
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $message
     * @return float
     * @throws \Throwable
     */
    public static function float(mixed $value, string $message = null): float
    {
        if (!\is_float($value)) {
            static::throwWrongTypeException(\sprintf(
                $message ?? 'Expected a float. Got: %s',
                static::typeToString($value)
            ));
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $message
     * @return string
     * @throws \Throwable
     */
    public static function string(mixed $value, string $message = null): string
    {
        if (!\is_string($value)) {
            static::throwWrongTypeException(\sprintf(
                $message ?? 'Expected a string. Got: %s',
                static::typeToString($value)
            ));
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $message
     * @return object
     * @phpstan-assert object $value
     * @throws \Throwable
     */
    public static function object(mixed $value, string $message = null): object
    {
        if (!\is_object($value)) {
            static::throwWrongTypeException(\sprintf(
                $message ?? 'Expected an object. Got: %s',
                static::typeToString($value)
            ));
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $message
     * @return mixed[]
     * @throws \Throwable
     */
    public static function array(mixed $value, string $message = null): array
    {
        if (!\is_array($value)) {
            static::throwWrongTypeException(\sprintf(
                $message ?? 'Expected an array. Got: %s',
                static::typeToString($value)
            ));
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @param string|null $message
     * @return bool
     * @throws \Throwable
     */
    public static function bool(mixed $value, string $message = null): bool
    {
        if (!\is_bool($value)) {
            static::throwWrongTypeException(\sprintf(
                $message ?? 'Expected a boolean. Got: %s',
                static::typeToString($value)
            ));
        }

        return (bool)$value;
    }

    /**
     * @param mixed $value
     * @param string|null $message
     * @return null
     * @throws \Throwable
     */
    public static function null(mixed $value, string $message = null)
    {
        if (!\is_null($value)) {
            static::throwWrongTypeException(\sprintf(
                $message ?? 'Expected a null. Got: %s',
                static::typeToString($value)
            ));
        }

        return null;
    }

    /**
     * @param mixed $value
     * @return MixedXInterface
     */
    public static function mixedX(mixed $value): MixedXInterface
    {
        return MixedX::new($value);
    }

    /**
     * @param mixed $value
     * @param string|null $message
     * @return ArrayXInterface<int|string,mixed>
     * @throws \Throwable
     */
    public static function arrayX(mixed $value, string $message = null): ArrayXInterface
    {
        return ArrayX::new($value, $message);
    }

    /**
     * @param mixed $value
     * @param string|null $message
     * @return ObjectXInterface
     * @throws \Throwable
     */
    public static function objectX(mixed $value, string $message = null): ObjectXInterface
    {
        return ObjectX::new($value, $message);
    }

    /**
     * @throws \Throwable
     * @return never-return
     */
    public static function throwWrongTypeException(string $message): void
    {
        throw new WrongTypeException($message);
    }

    /**
     * @param mixed $value
     * @return string
     */
    protected static function typeToString(mixed $value): string
    {
        return \is_object($value) ? \get_class($value) : \gettype($value);
    }

    private function __construct()
    {
    }
}