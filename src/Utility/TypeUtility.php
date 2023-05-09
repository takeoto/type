<?php

declare(strict_types=1);

namespace Takeoto\Type\Utility;

class TypeUtility
{
    # arrayXGetArrayGetString(['key0.0' => [ 'key0.1' => 'value']], 'key0.0', true, 'key0.1') > "value"
    # arrayXGetArrayGet      (['key0.0' => [ 'key0.1' => 'value']], 'key0.0', true, 'key0.1') > MixedX
    # arrayXGetArray         (['key0.0' => [ 'key0.1' => 'value']], 'key0.0', true)           > ArrayX
    # arrayXGet              (['key0.0' => [ 'key0.1' => 'value']], 'key0.0')                 > MixedX
    # arrayX                 (['key0.0' => [ 'key0.1' => 'value']])                           > ArrayX
    public static function chainCall(string $method, array $arguments, object|string $class): mixed
    {
        $commandMethods = preg_split('/(?=[A-Z])/', $method);

        while (count($commandMethods) > 0) {
            $callerMethods = array_flip(get_class_methods($class));
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

                if (!isset($callerMethods[$newMethod])) {
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

            if (!method_exists($class, $callMethod)) {
                throw new \Exception('Method does not exist: '. $callMethod);

            }

            $class = call_user_func([$class, $callMethod], ...array_splice($arguments, 0, $sequenceCount));
            array_splice($commandMethods, 0, $methodIndex + 1);
        }

        return $class;
    }
}