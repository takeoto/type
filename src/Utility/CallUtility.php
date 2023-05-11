<?php

declare(strict_types=1);

namespace Takeoto\Type\Utility;

use Takeoto\Type\Contract\MagicCallableInterface;

/**
 * @internal
 */
final class CallUtility
{
    /**
     * arrayXGetArrayXGetString(['key0.0' => [ 'key0.1' => 'value']], 'key0.0', 'key0.1') > "value"
     * arrayXGetArrayXGet      (['key0.0' => [ 'key0.1' => 'value']], 'key0.0', 'key0.1') > MixedX
     * arrayXGetArrayX         (['key0.0' => [ 'key0.1' => 'value']], 'key0.0')           > ArrayX
     * arrayXGet               (['key0.0' => [ 'key0.1' => 'value']], 'key0.0')           > MixedX
     * arrayX                  (['key0.0' => [ 'key0.1' => 'value']])                     > ArrayX
     *
     * @param string $method
     * @param mixed[] $arguments
     * @param object|string $target
     * @return mixed
     * @throws \Exception
     */
    public static function callChain(string $method, array $arguments, object|string $target): mixed
    {
        $commandMethods = iterator_to_array(self::iterateMethodParts($method));

        while (count($commandMethods) > 0) {
            if (!is_object($target) && !is_string($target)) {
                throw new \RuntimeException(sprintf('Can not call %s type.', TypeUtility::typeToString($target)));
            }

            $callerMethods = array_flip(get_class_methods($target));
            $callMethod = '';
            $argsCount = 0;
            $composedMethod = '';
            $methodIndex = 0;
            $sequenceCount = 0;

            foreach ($commandMethods as $index => $method) {
                if (isset($callerMethods[$method])) {
                    ++$sequenceCount;
                }

                $composedMethod = $composedMethod === '' ? $method : $composedMethod . ucfirst($method);
                $method = $callMethod === '' ? $composedMethod : $callMethod . ucfirst($composedMethod);
                $doesMethodExist = isset($callerMethods[$method])
                    || $target instanceof MagicCallableInterface
                    && $target->supportMagicCall($method, array_slice($arguments, 0, $argsCount + $sequenceCount));

                if (!$doesMethodExist) {
                    continue;
                }

                $methodIndex = $index;
                $callMethod = $method;
                $argsCount += $argsCount === 0 ? 1 : $sequenceCount;
                $sequenceCount = 0;
                $composedMethod = '';
            }

            if ($callMethod === '') {
                throw new \Exception('Method does not exist: ' . $composedMethod);
            }

            $callIt = method_exists($target, $callMethod)
                || (is_object($target) && method_exists($target, '__call'))
                || (is_string($target) && class_exists($target) && method_exists($target, '__callStatic'));
            $callback = [$target, $callMethod];

            if (!$callIt || !is_callable($callback)) {
                throw new \Exception('Method does not exist: ' . $callMethod);

            }

            $target = call_user_func($callback, ...array_splice($arguments, 0, $argsCount));
            array_splice($commandMethods, 0, $methodIndex + 1);
        }

        return $target;
    }

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
            var_dump($type);
            if ($type === '' || !TypeUtility::hasType($type)) {
                return false;
            }
        }

        return true;
    }

    private static function iterateMethodParts(string $method, string $delimiter = null): iterable
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
}