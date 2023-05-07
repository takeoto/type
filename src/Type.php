<?php

declare(strict_types=1);

namespace Takeoto\Type;

use Takeoto\Type\Contract\MixedXInterface;
use Takeoto\Type\Type\MixedX;

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
            static::throwInvalidException(\sprintf(
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
            static::throwInvalidException(\sprintf(
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
            static::throwInvalidException(\sprintf(
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
     * @throws \Throwable
     */
    public static function object(mixed $value, string $message = null): object
    {
        if (!\is_object($value)) {
            static::throwInvalidException(\sprintf(
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
            static::throwInvalidException(\sprintf(
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
            static::throwInvalidException(\sprintf(
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
            static::throwInvalidException(\sprintf(
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
    public static function mixed(mixed $value): MixedXInterface
    {
        return MixedX::new($value);
    }

    /**
     * @param mixed $value
     * @return string
     */
    protected static function typeToString(mixed $value): string
    {
        return \is_object($value) ? \get_class($value) : \gettype($value);
    }

    /**
     * @throws \Throwable
     * @return never-return
     */
    protected static function throwInvalidException(string $message): void
    {
        throw new \RuntimeException($message);
    }

    private function __construct()
    {
    }
}