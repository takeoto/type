<?php

declare(strict_types=1);

namespace Takeoto\Type\Utility;

use Takeoto\Type\Contract\MagicCallableInterface;
use Takeoto\Type\Contract\MagicStaticCallableInterface;

/**
 * @internal
 */
final class CallUtility
{
    /**
     * @param string $method
     * @param mixed[] $arguments
     * @return mixed
     * @throws \Throwable
     */
    public static function strictTypeCall(string $method, array $arguments): mixed
    {
        if (!array_key_exists(0, $arguments)) {
            throw new \InvalidArgumentException(sprintf('The first argument of %s method should be a value.', $method));
        }

        $value = $arguments[0];
        $error = $arguments[1] ?? 'The value should be one of types %s. Got: %s';

        if (!is_string($error) && $error !== null) {
            throw new \InvalidArgumentException(sprintf(
                'The second argument of %s method should be an error message.',
                $method,
            ));
        }

        $types = [];

        foreach (self::iterateMethodParts($method, 'Or') as $type) {
            $types[$type] = $type;

            if ($isNegative = str_starts_with($type, 'not')) {
                $type = lcfirst(substr($type, 3));
            }

            if (TypeUtility::verifyType($value, $type) xor $isNegative) {
                return $value;
            }
        }

        TypeUtility::throwWrongTypeException(\sprintf($error, implode('|', $types), TypeUtility::typeToString($value)));
    }

    public static function callTransit(
        string $method,
        array $arguments,
        string|object $target,
        \Closure $methodVerifier = null
    ): mixed {
        CallUtility::ensureCallable($target);
        $targetMethod = CallUtility::parseMethod($method, $target, $methodVerifier);

        if ($targetMethod === null) {
            throw new \RuntimeException(sprintf('The method "%s" does not exist.', $method));
        }

        $target = CallUtility::call($targetMethod, $target, [array_shift($arguments)]);
        CallUtility::ensureCallable($target);

        return CallUtility::call($method, $target, $arguments);
    }

    public static function callChain(
        string $method,
        array $arguments,
        string|object $target,
        \Closure $methodVerifier = null
    ): mixed {
        while (true) {
            CallUtility::ensureCallable($target);
            $targetMethod = CallUtility::parseMethod($method, $target, $methodVerifier);

            if ($targetMethod === null) {
                break;
            }

            CallUtility::ensureMethodExists($targetMethod, $target);
            $target = CallUtility::call($targetMethod, $target, [array_shift($arguments)]);
        }

        if ($method !== '') {
            throw new \RuntimeException(sprintf('Method "%s" is not part of chain call.', $method));
        }

        return $target;
    }

    public static function call(string $method, string|object $target, array $arguments = []): mixed
    {
        self::ensureCallable($target);
        self::ensureMethodExists($method, $target);

        return call_user_func([$target, $method], ...$arguments);
    }

    /**
     * @param string $method
     * @return bool
     */
    public static function isStrictTypeCall(string $method): bool
    {
        foreach (self::iterateMethodParts($method, 'Or') as $type) {
            if (str_starts_with($type, 'not')) {
                $type = lcfirst(substr($type, 3));
            }

            if ($type === '' || !TypeUtility::hasType($type)) {
                return false;
            }
        }

        return true;
    }

    public static function isTransitCall(string $method, string|object $target): bool
    {
        $composedMethod = null;

        foreach (self::iterateMethodParts($method) as $method) {
            $composedMethod = $composedMethod === null ? $method : $composedMethod . ucfirst($method);

            if (method_exists($target, $composedMethod)) {
                return true;
            }
        }

        return false;
    }

    public static function isChainCall(string $method, \Closure $methodVerifier): bool
    {
        $composedMethod = '';
        $methodsExistPositions = [];
        $methodParts = iterator_to_array(self::iterateMethodParts($method));

        for ($position = 0, $till = count($methodParts) - 1; $position <= $till; $position++) {
            echo PHP_EOL, PHP_EOL;
            $part = $methodParts[$position];
            echo '$part > ' . $part . PHP_EOL;
            $composedMethod = $composedMethod === '' ? $part : $composedMethod . ucfirst($part);
            echo '$composedMethod > ' . $composedMethod . PHP_EOL;

            if ($methodVerifier($composedMethod, $position, $till) === true) {
                $methodsExistPositions[] = $position;
                echo 'SUPPORT > ' . $composedMethod . PHP_EOL;
                continue;
            }

            if ($position !== $till) {
                continue;
            }

            var_dump($methodsExistPositions);
            $position = array_pop($methodsExistPositions);
            echo 'array_pop($methodsExistPositions) > ' . $position . PHP_EOL;
            $composedMethod = '';

            if ($position === null) {
                return false;
            }
        }

        return true;
    }

    public static function parseMethod(string &$method, string|object $class, ?\Closure $verifier = null): ?string
    {
        $verifier ??= fn(string $method): bool => method_exists($class, $method);
        $composedMethod = null;
        $composedMethodDraft = '';

        foreach (self::iterateMethodParts($method) as $part) {
            $composedMethodDraft = $composedMethodDraft === '' ? $part : $composedMethodDraft . ucfirst($part);

            if (!$verifier($composedMethodDraft)) {
                continue;
            }

            $composedMethod = $composedMethodDraft;
        }

        $method = lcfirst(substr($method, strlen($composedMethod ?? '')) ?: '');

        return $composedMethod;
    }

    public static function iterateMethodParts(string $method, string $delimiter = null): iterable
    {
        $methodParts = preg_split('/(?=[A-Z])/', $method) ?: [];

        if ($delimiter === null || $delimiter === '') {
            yield from array_map('lcfirst', $methodParts);
            return;
        }

        $composedType = '';

        foreach ($methodParts as $part) {
            if ($part === $delimiter) {
                yield lcfirst($composedType);
                $composedType = '';
            } else {
                $composedType = $composedType === '' ? $part : $composedType . $part;
            }
        }

        yield lcfirst($composedType);
    }

    public static function ensureCallable(mixed $target, string $error = '%s is not callable.'): void
    {
        if (!(is_object($target) || (is_string($target) && class_exists($target)))) {
            throw new \RuntimeException(sprintf($error, TypeUtility::typeToString($target)));
        }
    }

    public static function ensureMethodExists(string $method, string|object $target): void
    {
        if (!(method_exists($target, $method) || self::isSupportMagicMethod($method, $target))) {
            throw new \RuntimeException(sprintf(
                'The method %s::%s does not exist.',
                is_string($target) ? $target : get_class($target),
                $method,
            ));
        }
    }

    private static function isSupportMagicMethod(string $method, string|object $target): bool
    {
        return ($target instanceof MagicCallableInterface && $target->supportMagicCall($method))
            || (is_a($target, MagicStaticCallableInterface::class) && $target::supportMagicStaticCall($method));
    }
}