<?php

declare(strict_types=1);

namespace Takeoto\Type\Utility;

use Takeoto\Type\Contract\PredictableMagicCallInterface;
use Takeoto\Type\Exception\WrongTypeException;

/**
 * @internal
 */
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

    # arrayXGetArrayXGetString(['key0.0' => [ 'key0.1' => 'value']], 'key0.0', 'key0.1') > "value"
    # arrayXGetArrayXGet      (['key0.0' => [ 'key0.1' => 'value']], 'key0.0', 'key0.1') > MixedX
    # arrayXGetArrayX         (['key0.0' => [ 'key0.1' => 'value']], 'key0.0')           > ArrayX
    # arrayXGet               (['key0.0' => [ 'key0.1' => 'value']], 'key0.0')           > MixedX
    # arrayX                  (['key0.0' => [ 'key0.1' => 'value']])                     > ArrayX
    public static function callChain(string $method, array $arguments, object|string $target): mixed
    {
        $commandMethods = self::parseMethod($method);

        while (count($commandMethods) > 0) {
            $callerMethods = array_flip(get_class_methods($target));
            $callMethod = '';
            $callMethodDraft = '';
            $methodIndex = 0;
            $sequenceCount = 0;
            $isSequence = 1;

            foreach ($commandMethods as $index => $method) {
                $method = lcfirst($method);

                if (isset($callerMethods[$method])) {
                    $sequenceCount += $isSequence;
                } else {
                    $isSequence = 0;
                }

                $callMethodDraft = $callMethodDraft === '' ? $method : $callMethodDraft . ucfirst($method);
                $newMethod = $callMethod === '' ? $callMethodDraft : $callMethod . ucfirst($callMethodDraft);
                $doesMethodExist = isset($callerMethods[$newMethod])
                    || $target instanceof PredictableMagicCallInterface
                    && $target->supportMagicCall($newMethod, array_slice($arguments, 0, $sequenceCount));

                if (!$doesMethodExist) {
                    continue;
                }

                if ($isSequence === 0) {
                    $sequenceCount = 1;
                    $isSequence = 1;
                }

                $methodIndex = $index;
                $callMethod = $newMethod;
                var_dump($callMethod);
                $callMethodDraft = '';
            }

            if (!method_exists($target, $callMethod)) {
                throw new \Exception('Method does not exist: ' . $callMethod);

            }

            $target = call_user_func([$target, $callMethod], ...array_splice($arguments, 0, $sequenceCount));
            array_splice($commandMethods, 0, $methodIndex + 1);
        }

        return $target;
    }

    public static function parseMethod(string $method): array
    {
        return preg_split('/(?=[A-Z])/', $method);
    }

    public static function hasType(string $type): bool
    {
        return isset(self::TYPES_VERIFIERS[$type]);
    }

    public static function verifyType(mixed $value, string $type): bool
    {
        $condition = self::TYPES_VERIFIERS[$type] ?? throw new \Exception(sprintf('Unknown type %s', $type));

        return $condition($value) === true;
    }

    public static function normalizeType(string $type): array
    {
        return array_map(
            fn(string $type): string => self::hasType($normalizedType = strtolower($type)) ? $normalizedType : $type,
            explode('|', $type),
        );
    }

    public static function ensureType(mixed $value, string $type, ?string $errorMessage = null): void
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