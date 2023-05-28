<?php

declare(strict_types=1);

namespace Takeoto\Type\Utility;

use Takeoto\Type\Dictionary\TypeDict;
use Takeoto\Type\Exception\WrongTypeException;

class TypeUtility
{
    public const EXPR_TYPE_MODIFIER = 'type-modifier';
    public const EXPR_TYPE = 'type';
    public const EXPR_CAUSE = 'clause';
    public const EXPR_TYPE_MODIFIER_NOT = 'not';
    public const EXPR_CAUSE_AND = '&';
    public const EXPR_CAUSE_OR = '|';
    public const TYPES_VERIFIERS = [
        # base
        TypeDict::MIXED => [self::class, 'isMixed'],
        TypeDict::BOOL => 'is_bool',
        TypeDict::INT => 'is_int',
        TypeDict::FLOAT => 'is_float',
        TypeDict::STRING => 'is_string',
        TypeDict::ARRAY => 'is_array',
        TypeDict::OBJECT => 'is_object',
        TypeDict::NULL => 'is_null',
        TypeDict::CALLABLE => 'is_callable',
        # system
        TypeDict::ITERABLE => 'is_iterable',
        TypeDict::NUMERIC => 'is_numeric',
        TypeDict::TRUE => [self::class, 'isTrue'],
        TypeDict::FALSE => [self::class, 'isFalse'],
        # custom
        TypeDict::STRING_INT => [self::class, 'isStringInt'],
        TypeDict::EMPTY => [self::class, 'isEmpty'],
    ];

    public static function isTrue(mixed $value): bool
    {
        return $value === true;
    }

    public static function isFalse(mixed $value): bool
    {
        return $value === false;
    }

    public static function isStringInt(mixed $value): bool
    {
        return is_string($value) && preg_match('/^[0-9]+$/', $value);
    }

    public static function isMixed(mixed $value): bool
    {
        return true;
    }

    public static function isEmpty(mixed $value): bool
    {
        return empty($value);
    }

    /**
     * @param mixed $value
     * @param string $type
     * @param string|null $errorMessage
     * @return void
     * @throws \Throwable
     */
    public static function ensure(mixed $value, string $type, ?string $errorMessage = 'Expected %s, got %s'): void
    {
        if (self::verifyType($value, $type)) {
            return;
        }

        self::throwWrongTypeException(\sprintf($errorMessage, $type, self::typeToString($value)));
    }

    public static function verifyType(mixed $value, string $type): bool
    {
        $expression = self::expressionToTypesSequence($type);

        return $expression !== null && self::verifyTypeExpression($value, $expression);
    }

    private static function verifyTypeExpression(mixed $data, array $array): bool
    {
        $type = $array['type'];
        $value = $array['value'];

        switch ($type) {
            case self::EXPR_CAUSE:
                $state = false;

                foreach ($array['parts'] as $part) {
                    if ($value === self::EXPR_CAUSE_AND && !$state) {
                        continue;
                    }

                    $status = self::verifyTypeExpression($data, $part);
                    $state = $value === self::EXPR_CAUSE_AND ? $status : $state || $status;
                }

                return $state;
            case self::EXPR_TYPE:
                ['modifier' => $modifier, 'type' => $type] = $value;
                $verifier = self::TYPES_VERIFIERS[$type] ?? throw new \Exception(sprintf('Unknown type %s', $type));

                if (!is_callable($verifier)) {
                    throw new \Exception(sprintf('The %s type verifier should be callable.', $type));
                }

                return (call_user_func($verifier, $data) xor $modifier === self::EXPR_TYPE_MODIFIER_NOT);
            default:
                throw new \RuntimeException('Unexpectable type.');
        }
    }

    public static function hasType(string $type): bool
    {
        return isset(self::TYPES_VERIFIERS[$type]);
    }

    /**
     * @param string $expression
     * @param array<string,\Closure(string $v):bool> $typeDefiners
     * @return null|mixed[]
     */
    public static function expressionToTypesSequence(string $expression, array $typeDefiners = []): ?array
    {
        $parsedParts = self::parseExpression($expression, $typeDefiners);

        if (null === $parsedParts) {
            return null;
        }

        $groups = &$result;
        $groups = [$parsedParts];
        $clauses = [self::EXPR_CAUSE_OR, self::EXPR_CAUSE_AND];

        for ($i = 0, $last = count($clauses) - 1; $last >= $i; $i++) {
            $clause = $clauses[$i];
            $isLastCause = $last === $i;
            $newGroups = [];

//            echo PHP_EOL, PHP_EOL, '$groups > ' . var_export($groups, true), PHP_EOL;

            foreach ($groups as &$parts) {
                $newParts = [];
                $group = [];
                $modifier = null;

//                echo '$parts > ' . var_export($parts, true), PHP_EOL;

                for ($position = 0, $till = count($parts); $till > $position; $position++) {
                    ['type' => $type, 'value' => $value] = $part = $parts[$position];
//                    echo '$part > ' . var_export($part, true), PHP_EOL;

                    switch (true) {
                        case $type === self::EXPR_TYPE_MODIFIER:
                            $modifier = $value;
                            break;
                        case $type === self::EXPR_TYPE && !is_array($value):
                            $group[] = [
                                'type' => self::EXPR_TYPE,
                                'value' => ['modifier' => $modifier, 'type' => $value],
                            ];
                            $modifier = null;
                            break;
                        case $type === self::EXPR_CAUSE && $value === $clause:
                            $newParts[] = count($group) === 1 && $isLastCause ? reset($group) : $group;
                            $group = [];
                            break;
                        default:
                            $group[] = $part;
                    }
                }

                if (!empty($group)) {
                    $newParts[] = count($group) === 1 && $isLastCause ? reset($group) : $group;
                }

                if (count($newParts) === 1) {
                    $parts = reset($newParts);
                    continue;
                }

                $parts = ['type' => self::EXPR_CAUSE, 'value' => $clause, 'parts' => $newParts];

                foreach ($parts['parts'] as &$partRef) {
                    $newGroups[] = &$partRef;
                }
            }

            unset($groups);
            $groups = $newGroups;
            echo PHP_EOL,PHP_EOL,PHP_EOL;
        }

        return reset($result);
    }

    /**
     * @param string $expression
     * @param array<string,\Closure(string $v):bool> $typeDefiners
     * @return null|array{type: string, value: string}
     */
    public static function parseExpression(string $expression, array $typeDefiners = []): ?array
    {
        $expectNext = [self::EXPR_TYPE_MODIFIER => true, self::EXPR_TYPE => true];
        $typeDefiners += [
            self::EXPR_TYPE_MODIFIER => fn(string $v): bool => $v === self::EXPR_TYPE_MODIFIER_NOT,
            self::EXPR_TYPE => fn(string $v): bool => self::hasType($v),
            self::EXPR_CAUSE => fn(string $v): bool => [
                self::EXPR_CAUSE_AND => true,
                self::EXPR_CAUSE_OR => true,
            ][$v] ?? false,
        ];
        $parsedExpression = [];
        $parsedValue = $lastType = $lastValue = $lastPosition = null;

        for ($position = 0, $till = strlen($expression) - 1; $till >= $position; $position++) {
            $symbol = $expression[$position];
            $parsedValue = $parsedValue === null ? strtolower($symbol) : $parsedValue . $symbol;

            foreach ($typeDefiners as $type => $definer) {
                if (!$definer($parsedValue)) {
                    continue;
                }

                $lastPosition = $position;
                $lastType = $type;
                $lastValue = $parsedValue;
            }

            if ($till > $position) {
                continue;
            }

            if (null === $lastType) {
                break;
            }

            if (!$expectNext[$lastType] ?? false) {
                return null;
            }

            switch ($lastType) {
                case self::EXPR_CAUSE:
                    $expectNext = [self::EXPR_TYPE_MODIFIER => true, self::EXPR_TYPE => true];
                    break;
                case self::EXPR_TYPE_MODIFIER:
                    $expectNext = [self::EXPR_TYPE => true];
                    break;
                case self::EXPR_TYPE:
                    $expectNext = [self::EXPR_CAUSE => true];
                    break;
                default:
                    throw new \LogicException(sprintf('Unexpected type "%s"', $lastType));
            }

            $position = $lastPosition;
            $parsedExpression[] = ['type' => $lastType, 'value' => $lastValue];
            $parsedValue = $lastType = $lastValue = $lastPosition = null;
        }

        if (null !== $parsedValue) {
            return null;
        }

        $lastType = end($parsedExpression)['type'] ?? null;

        if ($lastType !== self::EXPR_TYPE) {
            return null;
        }

        reset($parsedExpression);

        return $parsedExpression;
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