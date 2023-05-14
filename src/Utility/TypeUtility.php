<?php

declare(strict_types=1);

namespace Takeoto\Type\Utility;

use Takeoto\Type\Exception\WrongTypeException;

class TypeUtility
{
    # base
    public const TYPE_BOOL = 'bool';
    public const TYPE_INT = 'int';
    public const TYPE_FLOAT = 'float';
    public const TYPE_STRING = 'string';
    public const TYPE_ARRAY = 'array';
    public const TYPE_OBJECT = 'object';
    public const TYPE_NULL = 'null';
    public const TYPE_CALLABLE = 'callable';
    # pseudo
    public const TYPE_ITERABLE = 'iterable';
    public const TYPE_NUMERIC = 'numeric';
    public const TYPE_TRUE = 'true';
    public const TYPE_FALSE = 'false';
    # custom
    public const TYPE_STRING_INT = 'stringInt';
    public const TYPE_EMPTY = 'empty';
    public const TYPES_VERIFIERS = [
        # base
        self::TYPE_BOOL => 'is_bool',
        self::TYPE_INT => 'is_int',
        self::TYPE_FLOAT => 'is_float',
        self::TYPE_STRING => 'is_string',
        self::TYPE_ARRAY => 'is_array',
        self::TYPE_OBJECT => 'is_object',
        self::TYPE_NULL => 'is_null',
        self::TYPE_CALLABLE => 'is_callable',
        # system
        self::TYPE_ITERABLE => 'is_iterable',
        self::TYPE_NUMERIC => 'is_numeric',
        self::TYPE_TRUE => [self::class, 'isTrue'],
        self::TYPE_FALSE => [self::class, 'isFalse'],
        # custom
        self::TYPE_STRING_INT => [self::class, 'isStringInt'],
        self::TYPE_EMPTY => 'empty',
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

    /**
     * @param mixed $value
     * @param string[]|string $type
     * @param string|null $errorMessage
     * @return void
     * @throws \Throwable
     */
    public static function ensure(mixed $value, array|string $type, ?string $errorMessage = null): void
    {
        $types = array_reduce((array)$type, fn(array $carry, string $type): array => array_merge(
            self::normalizeType($type),
            $carry,
        ), []);

        foreach ((array)$type as $type) {
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