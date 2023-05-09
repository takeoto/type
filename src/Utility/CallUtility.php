<?php

declare(strict_types=1);

namespace Takeoto\Type\Utility;

use Takeoto\Type\Contract\PredictableMagicCallInterface;

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
}