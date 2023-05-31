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
    public const EXPR_CAUSE_AND = 'and';
    public const EXPR_CAUSE_OR = 'or';

    /**
     * @param string $expression
     * @param mixed[] $arguments
     * @return mixed
     * @throws \Throwable
     */
    public static function strictTypeCall(string $expression, array $arguments): mixed
    {
        if (!array_key_exists(0, $arguments)) {
            throw new \InvalidArgumentException(sprintf(
                'The first argument of %s method should be a value.',
                $expression,
            ));
        }

        $value = $arguments[0];
        $error = $arguments[1] ?? null;

        if (!is_string($error) && $error !== null) {
            throw new \InvalidArgumentException(sprintf(
                'The second argument of %s method should be an error message.',
                $expression,
            ));
        }

        TypeUtility::ensure($value, self::typeExpressionCallToType($expression), $error);

        return $value;
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

        self::ensureClassOrObject($target);

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
        self::ensureClassOrObject($target);
        self::ensureMethodExists($method, $target);

        /** @var callable $callable */
        $callable = [$target, $method];

        # verify schema
        return call_user_func_array($callable, $arguments);
    }

    /**
     * @param string $method
     * @return bool
     */
    public static function isTypeExpressionCall(string $method): bool
    {
        return null !== self::parseTypeExpressionCall($method);
    }

    /**
     * @param string $method
     * @param class-string|object $target
     * @return bool
     */
    public static function isTransitCall(string $method, string|object $target): bool
    {
        self::ensureTransitional($target);
        /** @var TransitionalInterface $target */
        $composedMethod = null;

        foreach (self::iterateMethodParts($method) as $method) {
            $composedMethod = $composedMethod === null ? $method : $composedMethod . ucfirst($method);

            if ($target::getMethodScheme($composedMethod) !== null) {
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
     * @return \Traversable<int,string>
     */
    private static function iterateMethodParts(string $method): \Traversable
    {
        $methodParts = preg_split('/(?=[A-Z])/', $method) ?: [];

        foreach ($methodParts as $methodPart) {
            yield lcfirst($methodPart);
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
     * @param class-string|object $target
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
            throw new \RuntimeException(sprintf(
                'Required %d arguments of "%s" method, %d given!',
                $schemesReqArgsCount,
                $method,
                $argumentsCount,
            ));
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
     * @param class-string $class
     * @return MethodSchemeInterface|null
     * P.S. I don't like a reflection API
     */
    public static function getSelfMethodSchema(string $method, string $class): ?MethodSchemeInterface
    {
        if (!method_exists($class, $schemeMethod = $method . 'Scheme')) {
            return null;
        }

        /** @var callable $caller */
        $caller = [$class, $schemeMethod];
        $scheme = call_user_func($caller);

        if (!$scheme instanceof MethodSchemeInterface) {
            throw new \LogicException(sprintf(
                'The method scheme should be an instance of %s',
                MethodSchemeInterface::class,
            ));
        }

        return $scheme;
    }

    public static function typeExpressionCallToType(string $method): string
    {
        $parsedMethod = self::parseTypeExpressionCall($method)
            ?? throw new \LogicException(sprintf('Cannot convert a method "%s" to a type', $method));

        return array_reduce(
            $parsedMethod,
            function(array $carry, array $part): array {
                $value = $part['value'];

                switch ($part['type']) {
                    case TypeUtility::EXPR_CLAUSE:
                        $value = [
                            self::EXPR_CAUSE_OR => '|',
                            self::EXPR_CAUSE_AND => '&',
                        ][$value] ?? throw new \LogicException(sprintf('Unknown a value "%s" of a clause.', $value));
                        break;
                    case TypeUtility::EXPR_TYPE_MODIFIER:
                        $carry[1] = $value;
                        return $carry;
                    case TypeUtility::EXPR_TYPE:
                        $value = $carry[1] === null ? $value : $carry[1] . ucfirst($value);
                        $carry[1] = null;
                }

                # need improve [notInt&NotString - case]
                return [$carry[0] . $value, $carry[1]];
            },
            ['', null],
        )[0];
    }

    /**
     * @param string $method
     * @param class-string|object $target
     * @return \Traversable<string,null|MethodSchemeInterface>
     */
    private static function iterateMethodsSchemas(string $method, string|object $target): \Traversable
    {
        while ($method) {
            if (!self::isTransitional($target)) {
                break;
            }

            /** @var TransitionalInterface $target */
            $targetMethod = self::shiftTransitMethod($method, $target);

            if ($targetMethod === null) {
                break;
            }

            yield $targetMethod => $scheme = $target::getMethodScheme($targetMethod);
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
        /** @var TransitionalInterface $target */
        $targetMethod = self::parseMethod($method, $target, fn(string $m) => $target::getMethodScheme($m) !== null);
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
    private static function ensureClassOrObject(mixed $target): void
    {
        if (!self::isClassOrObject($target)) {
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
     * @param mixed $target
     * @phpstan-assert TransitionalInterface $target
     * @return void
     */
    private static function ensureTransitional(mixed $target): void
    {
        self::ensureClassOrObject($target);

        if (!self::isTransitional($target)) {
            throw new \LogicException(sprintf(
                'The value should be an instance of "%s"!',
                TransitionalInterface::class,
            ));
        }
    }

    /**
     * @param mixed $target
     * @return bool
     */
    private static function isTransitional(mixed $target): bool
    {
        return self::isClassOrObject($target) && is_subclass_of($target, TransitionalInterface::class);
    }

    /**
     * @param mixed $target
     * @return bool
     */
    private static function isClassOrObject(mixed $target): bool
    {
        return (is_object($target) || (is_string($target) && class_exists($target)));
    }

    /**
     * @param string $method
     * @return null|array<int,array{type: string, value: string}>
     * @throws \Throwable
     */
    private static function parseTypeExpressionCall(string $method): ?array
    {
        # @phpstan-ignore-next-line
        return TypeUtility::parseExpression($method, [
            TypeUtility::EXPR_CLAUSE => fn(string $v): ?string => [
                self::EXPR_CAUSE_AND => TypeUtility::EXPR_CLAUSE_AND,
                self::EXPR_CAUSE_OR => TypeUtility::EXPR_CLAUSE_OR,
            ][$v] ?? null,
        ]);
    }
}