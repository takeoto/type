<?php

declare(strict_types=1);

namespace Takeoto\Type\Utility;

use Takeoto\Type\Dictionary\TypeDict;
use Takeoto\Type\Exception\WrongTypeException;

class TypeUtility
{
    public const TYPES_VERIFIERS = [
        # base
        TypeDict::MIXED => [self::class, 'isMixed'],
        TypeDict::BOOL => 'is_bool',
        TypeDict::INT => 'is_int',
        TypeDict::FLOAT => 'is_float',
        TypeDict::STRING => 'is_string',
        TypeDict::ARRAY => 'is_array',
        TypeDict::OBJECT => 'is_object',
        TypeDict::NULL => 'is_null',
        TypeDict::CALLABLE => 'is_callable',
        # system
        TypeDict::ITERABLE => 'is_iterable',
        TypeDict::NUMERIC => 'is_numeric',
        TypeDict::TRUE => [self::class, 'isTrue'],
        TypeDict::FALSE => [self::class, 'isFalse'],
        # custom
        TypeDict::STRING_INT => [self::class, 'isStringInt'],
        TypeDict::EMPTY => 'empty',
    ];

    public static function isTrue(mixed $value): bool
    {
        return $value === true;
    }

    public static function isFalse(mixed $value): bool
    {
        return $value === false;
    }

    public static function isStringInt(mixed $value): bool
    {
        return is_string($value) && preg_match('/^[0-9]+$/', $value);
    }

    public static function isMixed(mixed $value): bool
    {
        return true;
    }

    /**
     * @param mixed $value
     * @param string[]|string $type
     * @param string|null $errorMessage
     * @return void
     * @throws \Throwable
     */
    public static function ensure(mixed $value, array|string $type, ?string $errorMessage = null): void
    {
        $types = array_reduce((array)$type, fn(array $carry, string $type): array => [
            ...self::normalizeType($type),
            ...$carry,
        ], []);

        foreach ($types as $type) {
            if (TypeUtility::verifyType($value, $type)) {
                return;
            }
        }

        if ($errorMessage === null) {
            $errorMessage = count($types) === 1
                ? 'Expected a %s. Got: %s' # need improve [a|an]
                : 'The value should be one of types %s. Got: %s';
        }

        TypeUtility::throwWrongTypeException(\sprintf(
            $errorMessage,
            implode('|', $types),
            TypeUtility::typeToString($value),
        ));
    }

    public static function verifyType(mixed $value, string $type): bool
    {
        $condition = self::TYPES_VERIFIERS[$type] ?? throw new \Exception(sprintf('Unknown type %s', $type));

        if (!is_callable($condition)) {
            throw new \Exception(sprintf('The %s type condition should be callable.', $type));
        }

        return call_user_func($condition, $value) === true;
    }

    public static function hasType(string $type): bool
    {
        return isset(self::TYPES_VERIFIERS[$type]);
    }

    /**
     * @param string $type
     * @return string[]
     */
    public static function normalizeType(string $type): array
    {
        return explode('|', $type);
    }

    public static function denormalizeType(string ...$types): string
    {
        return implode('|', $types);
    }

    /**
     * @param mixed $value
     * @return string
     */
    public static function typeToString(mixed $value): string
    {
        return \is_object($value) ? \get_class($value) : \gettype($value);
    }

    /**
     * @return never-return
     * @throws \Throwable
     */
    public static function throwWrongTypeException(string $message): void
    {
        throw new WrongTypeException($message);
    }
}