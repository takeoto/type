<?php

declare(strict_types=1);

namespace Takeoto\Type\Utility;

use Takeoto\Type\Exception\WrongTypeException;

class TypeUtility
{
    public const TYPE_BOOL = 'bool';
    public const TYPE_INT = 'int';
    public const TYPE_FLOAT = 'float';
    public const TYPE_STRING = 'string';
    public const TYPE_ARRAY = 'array';
    public const TYPE_OBJECT = 'object';
    public const TYPE_NULL = 'null';
    public const TYPES_VERIFIERS = [
        'bool' => 'is_bool',
        'int' => 'is_int',
        'float' => 'is_float',
        'string' => 'is_string',
        'array' => 'is_array',
        'object' => 'is_object',
        'null' => 'is_null',
    ];

    public static function ensure(mixed $value, string $type, ?string $errorMessage = null): void
    {
        $types = self::normalizeType($type);

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

        return $condition($value) === true;
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
        return array_map(
            fn(string $type): string => self::hasType($normalizedType = strtolower($type)) ? $normalizedType : $type,
            explode('|', $type),
        );
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