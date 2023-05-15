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
        $error = $arguments[1] ?? null;

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

        TypeUtility::throwWrongTypeException(\sprintf(
            $error ?? 'The value should be one of types %s. Got: %s',
            implode('|', $types),
            TypeUtility::typeToString($value)
        ));
    }

    /**
     * @param string $method
     * @param mixed[] $arguments
     * @param class-string|object $target
     * @param \Closure(string $method):bool|null $methodVerifier
     * @return mixed
     */
    public static function callTransit(
        string $method,
        array $arguments,
        string|object $target,
        \Closure $methodVerifier = null
    ): mixed {
        $targetMethod = CallUtility::parseMethod($method, $target, $methodVerifier);

        if ($targetMethod === null) {
            throw new \RuntimeException(sprintf('The method "%s" does not exist.', $method));
        }

        $targetArguments = CallUtility::retrieveArguments($targetMethod, $target, $arguments);
        $target = CallUtility::call($targetMethod, $target, $targetArguments);

        if ($method === '') {
            return $target;
        }

        CallUtility::ensureCallable($target);

        return CallUtility::call($method, $target, $arguments);
    }

    /**
     * @param string $method
     * @param class-string|object $target
     * @param mixed[] $arguments
     * @return mixed
     */
    public static function call(string $method, string|object $target, array $arguments = []): mixed
    {
        self::ensureCallable($target);
        self::ensureMethodExists($method, $target);

        /** @var callable $callable */
        $callable = [$target, $method];

        return call_user_func($callable, ...$arguments);
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

    /**
     * @param string $method
     * @param class-string|object $target
     * @param \Closure(string $method):bool|null $methodVerifier
     * @return bool
     */
    public static function isTransitCall(string $method, string|object $target, \Closure $methodVerifier = null): bool
    {
        $methodVerifier ??= fn(string $method): bool => method_exists($target, $method);
        $composedMethod = null;

        foreach (self::iterateMethodParts($method) as $method) {
            $composedMethod = $composedMethod === null ? $method : $composedMethod . ucfirst($method);

            if ($methodVerifier($composedMethod)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $method
     * @param string|object $class
     * @param \Closure(string $method):bool|null $verifier
     * @return string|null
     */
    private static function parseMethod(string &$method, string|object $class, ?\Closure $verifier = null): ?string
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

    /**
     * @param string $method
     * @param string|null $delimiter
     * @return \Traversable<int,string>
     */
    private static function iterateMethodParts(string $method, string $delimiter = null): \Traversable
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

    /**
     * @param mixed $target
     * @param string $error
     * @phpstan-assert class-string|object $target
     * @return void
     */
    private static function ensureCallable(mixed $target, string $error = '%s is not callable.'): void
    {
        if (!(is_object($target) || (is_string($target) && class_exists($target)))) {
            throw new \RuntimeException(sprintf($error, TypeUtility::typeToString($target)));
        }
    }

    /**
     * @param string $method
     * @param class-string|object $target
     * @return void
     */
    private static function ensureMethodExists(string $method, string|object $target): void
    {
        if (!(self::isSupportMethod($target, $method))) {
            throw new \RuntimeException(sprintf(
                'The method %s::%s does not exist.',
                is_string($target) ? $target : get_class($target),
                $method,
            ));
        }
    }

    /**
     * @param object|class-string $target
     * @param string $method
     * @return bool
     */
    public static function isSupportMethod(object|string $target, string $method): bool
    {
        return method_exists($target, $method) || self::isSupportMagicMethod($method, $target);
    }

    /**
     * @param string $method
     * @param class-string|object $target
     * @return bool
     */
    private static function isSupportMagicMethod(string $method, string|object $target): bool
    {
        return ($target instanceof MagicCallableInterface && $target->supportMagicCall($method))
            || (is_a($target, MagicStaticCallableInterface::class, true) && $target::supportMagicStaticCall($method));
    }

    /**
     * @param string $method
     * @param string|object $target
     * @param mixed[] $arguments
     * @return array
     */
    private static function retrieveArguments(string $method, string|object $target, array &$arguments): array
    {
        return [array_shift($arguments)];
    }
}