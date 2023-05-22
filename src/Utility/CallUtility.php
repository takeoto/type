<?php

declare(strict_types=1);

namespace Takeoto\Type\Utility;

use Takeoto\Type\Contract\MagicCallableInterface;
use Takeoto\Type\Contract\MagicStaticCallableInterface;
use Takeoto\Type\Contract\Scheme\MethodSchemeInterface;
use Takeoto\Type\Contract\TransitionalInterface;

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

        foreach (self::iterateMethodTypes($method) as $fullType => ['type' => $type, 'isNegative' => $isNegative]) {
            $types[] = $fullType;

            if ($type === null) {
                throw new \Exception(sprintf('Unknown type %s', $fullType));
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
     * @return mixed
     * @throws \Throwable
     */
    public static function callTransit(
        string $method,
        array $arguments,
        string|object $target
    ): mixed {
        self::ensureTransitional($target);
        $targetArguments = self::shiftTransitMethodArguments($method, $target, $arguments);
        $targetMethod = self::shiftTransitMethod($method, $target);

        if ($targetMethod === null) {
            throw new \RuntimeException(sprintf('The method "%s" does not exist.', $method));
        }

        $target = self::call($targetMethod, $target, $targetArguments);

        if ($method === '') {
            return $target;
        }

        self::ensureCallable($target);

        return self::call($method, $target, $arguments);
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

        return call_user_func_array($callable, $arguments);
    }

    /**
     * @param string $method
     * @return bool
     */
    public static function isStrictTypeCall(string $method): bool
    {
        foreach (self::iterateMethodTypes($method) as ['type' => $type]) {
            if (null === $type) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $method
     * @param class-string|object $target
     * @return bool
     */
    public static function isTransitCall(string $method, string|object $target): bool
    {
        self::ensureTransitional($target);

        $composedMethod = null;

        foreach (self::iterateMethodParts($method) as $method) {
            $composedMethod = $composedMethod === null ? $method : $composedMethod . ucfirst($method);

            if ($target::parseTransitMethod($composedMethod) !== null) {
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
    public static function parseMethod(string $method, string|object $class, ?\Closure $verifier = null): ?string
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
     * @return mixed[]
     * @throws \Throwable
     */
    private static function shiftTransitMethodArguments(string $method, string|object $target, array &$arguments): array
    {
        $argumentsCount = count($arguments);
        $schemesReqArgsCount = 0;
        $schemesArgsCount = 0;
        $schemes = [];

        foreach (self::iterateMethodsSchemas($method, $target) as $methodName => $methodScheme) {
            if ($methodScheme === null) {
                throw new \RuntimeException(sprintf(
                    'Arguments schemas for "%s" in "%s" method does not exists!',
                    $methodName,
                    $method,
                ));
            }

            foreach ($methodScheme->getArguments() as $argument) {
                $schemesArgsCount++;

                if (!$argument->hasDefault()) {
                    $schemesReqArgsCount++;
                }
            }

            $schemes[] = $methodScheme;
        }

        if ($schemesReqArgsCount > count($arguments)) {
            throw new \RuntimeException(sprintf('Required arguments of "%s" method more than given!', $method));
        }

        if ($argumentsCount > $schemesArgsCount) {
            throw new \RuntimeException(sprintf(
                'Arguments count of "%s" method %d, %d given!',
                $method,
                $schemesArgsCount,
                $argumentsCount,
            ));
        }

        $methodScheme = reset($schemes);

        if (!$methodScheme) {
            return [];
        }

        $args = [];

        foreach ($methodScheme->getArguments() as $arg) {
            $isRequired = !$arg->hasDefault();

            if ($isRequired || $argumentsCount > $schemesReqArgsCount) {
                TypeUtility::ensure(
                    $argValue = array_shift($arguments),
                    $arg->getType(),
                );
                $args[] = $argValue;
                $argumentsCount--;
                $schemesReqArgsCount -= (int)$isRequired;
                continue;
            }

            $args[] = $arg->getDefault();
        }

        return $args;
    }

    /**
     * @param string $method
     * @param string $class
     * @return MethodSchemeInterface|null
     */
    public static function getSelfMethodSchema(string $method, string $class): ?MethodSchemeInterface
    {
        if (!method_exists($class, $schemeMethod = $method . 'Scheme')) {
            return null;
        }

        $scheme = call_user_func([$class, $schemeMethod]);

        if (!$scheme instanceof MethodSchemeInterface) {
            throw new \LogicException(sprintf(
                'The method scheme should be an instance of %s',
                MethodSchemeInterface::class,
            ));
        }

        return $scheme;
    }

    /**
     * @param string $method
     * @return \Traversable<string,array{type: string|null, isNegative: bool}>
     */
    public static function iterateMethodTypes(string $method): \Traversable
    {
        foreach (self::iterateMethodParts($method, 'Or') as $fullType) {
            $type = str_starts_with($fullType, 'not') ? lcfirst(substr($fullType, 3)) : $fullType;

            yield $fullType => [
                'type' => $type === '' || !TypeUtility::hasType($type) ? null : $type,
                'isNegative' => $fullType !== $type,
            ];
        }
    }

    public static function methodTypesToType(string $method): string
    {
        return TypeUtility::denormalizeType(
            ...array_column(
                iterator_to_array(CallUtility::iterateMethodTypes($method)),
                'type',
            )
        );
    }

    /**
     * @param string $method
     * @param class-string|object $target
     * @return \Traversable<string,MethodSchemeInterface>
     */
    private static function iterateMethodsSchemas(string $method, string|object $target): \Traversable
    {
        while ($method) {
            self::ensureTransitional($target);
            /** @var TransitionalInterface $target */
            $targetMethod = self::shiftTransitMethod($method, $target);

            if ($targetMethod === null) {
                break;
            }

            yield $targetMethod => $scheme = $target::getTransitMethodScheme($targetMethod);
            $target = $scheme?->getReturnType();
        }

        if ($method !== '') {
            yield $method => null;
        }
    }

    /**
     * @param string $method
     * @param class-string|object $target
     * @return string|null
     */
    private static function shiftTransitMethod(string &$method, string|object $target): ?string
    {
        self::ensureTransitional($target);

        $targetMethod = $target::parseTransitMethod($method);
        $method = self::cutOffMethod($method, $targetMethod ?? '');

        return $targetMethod;
    }

    private static function cutOffMethod(string $fullMethod, string $subMethod): string
    {
        return lcfirst(substr($fullMethod, strlen($subMethod)) ?: '');
    }

    /**
     * @param mixed $target
     * @phpstan-assert class-string|object $target
     * @return void
     */
    private static function ensureCallable(mixed $target): void
    {
        if (!(is_object($target) || (is_string($target) && class_exists($target)))) {
            throw new \LogicException(sprintf('%s is not callable.', TypeUtility::typeToString($target)));
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
            throw new \LogicException(sprintf(
                'The method %s::%s does not exist.',
                is_string($target) ? $target : get_class($target),
                $method,
            ));
        }
    }

    /**
     * @param object|class-string $target
     * @phpstan-assert TransitionalInterface $target
     * @return void
     */
    private static function ensureTransitional(object|string $target): void
    {
        if (!is_subclass_of($target, TransitionalInterface::class)) {
            throw new \LogicException(sprintf(
                'The value should be an instance of "%s"!',
                TransitionalInterface::class,
            ));
        }
    }
}