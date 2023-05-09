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
        $commandMethods = self::parseMethod($method);

        while (count($commandMethods) > 0) {
            if (!is_object($target) && !is_string($target)) {
                throw new \RuntimeException(sprintf('Can not call %s type.', TypeUtility::typeToString($target)));
            }

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
                    || $target instanceof MagicCallableInterface
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
                $callMethodDraft = '';
            }

            $callIt = method_exists($target, $callMethod)
                || (is_object($target) && method_exists($target, '__call'))
                || (is_string($target) && class_exists($target) && method_exists($target, '__callStatic'));
            $callback = [$target, $callMethod];

            if (!$callIt || !is_callable($callback)) {
                throw new \Exception('Method does not exist: ' . $callMethod);

            }

            $target = call_user_func($callback, ...array_splice($arguments, 0, $sequenceCount));
            array_splice($commandMethods, 0, $methodIndex + 1);
        }

        return $target;
    }

    /**
     * @param string $method
     * @return string[]
     */
    public static function parseMethod(string $method): array
    {
        return preg_split('/(?=[A-Z])/', $method) ?: [];
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
            throw new \InvalidArgumentException(sprintf(
                'The first argument of %s method should be a value.',
                $method,
            ));
        }

        $value = $arguments[0];
        $errorMessage = $arguments[1] ?? 'The value should be one of types %s. Got: %s';
        TypeUtility::ensure(
            $errorMessage,
            TypeUtility::TYPE_STRING,
            sprintf('The second argument of %s method should be an error message.', $method),
        );
        $types = preg_split('/(?<!^|[A-Z^])Or(?=[A-Z])/', $method);

        if ($types === false || count($types) === 1) {
            throw new \RuntimeException(sprintf('Method "%s" does not exist.', $method));
        }

        foreach ($types as &$type) {
            if (TypeUtility::verifyType($value, $type = strtolower($type))) {
                return $value;
            }
        }

        TypeUtility::throwWrongTypeException(\sprintf(
            $errorMessage,
            implode('|', $types),
            TypeUtility::typeToString($value),
        ));
    }

    /**
     * @param string $method
     * @param mixed[] $arguments
     * @return bool
     */
    public static function isStrictTypeCall(string $method, array $arguments): bool
    {
        $methodParts = CallUtility::parseMethod($method);
        $shouldBeType = true;

        for ($i = count($methodParts) - 1; $i > 0; $i--) {
            $part = $methodParts[$i];
            $isTypeCall = $shouldBeType ? TypeUtility::hasType(strtolower($part)) : $part === 'Or';
            $shouldBeType = !$shouldBeType;

            if (!$isTypeCall) {
                return false;
            }
        }

        return true;
    }
}