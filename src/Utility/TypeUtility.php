<?php

declare(strict_types=1);

namespace Takeoto\Type\Utility;

use Takeoto\Type\Dictionary\TypeDict;
use Takeoto\Type\Exception\WrongTypeException;

class TypeUtility
{
    public const EXPR_TYPE_MODIFIER = 'type-modifier';
    public const EXPR_TYPE = 'type';
    public const EXPR_CLAUSE = 'clause';
    public const EXPR_TYPE_MODIFIER_NOT = 'not';
    public const EXPR_CLAUSE_AND = 'and';
    public const EXPR_CLAUSE_OR = 'or';
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
    public static function ensure(mixed $value, string $type, string $errorMessage = null): void
    {
        if (self::verifyType($value, $type)) {
            return;
        }

        self::throwWrongTypeException(\sprintf(
            $errorMessage ?? 'Expected %s type, %s given',
            $type,
            self::typeToString($value),
        ));
    }

    public static function verifyType(mixed $value, string $type): bool
    {
        $expression = self::expressionToTypesSequence($type);

        if ($expression === null) {
            throw new \InvalidArgumentException(sprintf('Unknown type %s.', $type));
        }

        return self::verifyTypeExpression($value, $expression);
    }

    public static function hasType(string $type): bool
    {
        return isset(self::TYPES_VERIFIERS[$type]);
    }

    /**
     * @param string $expression
     * @param array<string,\Closure(string $v):null|string> $typeDefiners
     * @return null|array<int,array{type: string, value: string}>
     * @throws \Throwable
     */
    public static function parseExpression(string $expression, array $typeDefiners = []): ?array
    {
        $expectNext = [self::EXPR_TYPE_MODIFIER => true, self::EXPR_TYPE => true];
        $typeDefiners += [
            self::EXPR_TYPE => fn(string $v): ?string => self::hasType($v) ? $v : null,
            self::EXPR_TYPE_MODIFIER => fn(string $v): ?string => [
                'not' => self::EXPR_TYPE_MODIFIER_NOT,
            ][$v] ?? null,
            self::EXPR_CLAUSE => fn(string $v): ?string => [
                '&' => self::EXPR_CLAUSE_AND,
                '|' => self::EXPR_CLAUSE_OR,
            ][$v] ?? null,
        ];
        $parsedExpression = [];
        $parsedValue = $lastType = $lastValue = $lastPosition = null;

        for ($position = 0, $till = strlen($expression) - 1; $till >= $position; $position++) {
            $symbol = $expression[$position];
            $parsedValue = $parsedValue === null ? strtolower($symbol) : $parsedValue . $symbol;

            foreach ($typeDefiners as $type => $definer) {
                if (!is_callable($definer)) {
                    throw new \Exception(sprintf('The %s type definer should be callable.', $type));
                }

                if (($value = $definer($parsedValue)) === null) {
                    continue;
                }

                $lastPosition = $position;
                $lastType = $type;
                $lastValue = $value;
            }

            if ($till > $position) {
                continue;
            }

            if (null === $lastType) {
                break;
            }

            if (!($expectNext[$lastType] ?? false)) {
                return null;
            }

            switch ($lastType) {
                case self::EXPR_CLAUSE:
                    $expectNext = [self::EXPR_TYPE_MODIFIER => true, self::EXPR_TYPE => true];
                    break;
                case self::EXPR_TYPE_MODIFIER:
                    $expectNext = [self::EXPR_TYPE => true];
                    break;
                case self::EXPR_TYPE:
                    $expectNext = [self::EXPR_CLAUSE => true];
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

    /**
     * @type
     * @param mixed $data
     * @param mixed[] $exp
     * @return bool
     * @throws \Throwable
     */
    private static function verifyTypeExpression(mixed $data, array $exp): bool
    {
        $type = $exp['type'] ?? throw new \LogicException('The "type" key is required."');;
        $value = $exp['value'] ?? throw new \LogicException('The "value" key is required."');;

        switch ($type) {
            case self::EXPR_CLAUSE:
                $state = null;
                $parts = $exp['parts'] ?? throw new \LogicException('The "parts" key is required."');

                foreach ($parts as $part) {
                    if ($value === self::EXPR_CLAUSE_AND && $state === false) {
                        break;
                    }

                    $status = self::verifyTypeExpression($data, $part);
                    $state = $value === self::EXPR_CLAUSE_AND ? $status : $state || $status;
                }

                return $state ?? false;
            case self::EXPR_TYPE:
                $modifier = $value['modifier'] ?? null;
                $type = $value['type'] ?? throw new \LogicException('The "type" key is required."');
                $verify = self::TYPES_VERIFIERS[$type] ?? throw new \LogicException(sprintf('Unknown type %s', $type));

                if (!is_callable($verify)) {
                    throw new \Exception(sprintf('The %s type verifier should be callable.', $type));
                }

                return (call_user_func($verify, $data) xor $modifier === self::EXPR_TYPE_MODIFIER_NOT);
            default:
                throw new \RuntimeException(sprintf('Unexpectable type %s.', var_export($type, true)));
        }
    }

    /**
     * @param string $expression
     * @param array<string,\Closure(string $v):null|string> $typeDefiners
     * @return null|mixed[]
     * @throws \Throwable
     */
    private static function expressionToTypesSequence(string $expression, array $typeDefiners = []): ?array
    {
        $parsedParts = self::parseExpression($expression, $typeDefiners);

        if (null === $parsedParts) {
            return null;
        }

        $result = [$parsedParts];
        $groups = &$result;
        $clauses = [self::EXPR_CLAUSE_OR, self::EXPR_CLAUSE_AND];

        for ($position = 0, $last = count($clauses) - 1; $last >= $position; $position++) {
            $clause = $clauses[$position];
            $isLastClause = $position === $last;
            $newGroups = [];

            foreach ($groups as &$parts) {
                $newGroups = [];
                $group = [];
                $modifier = null;

                foreach ($parts as $part) {
                    ['type' => $type, 'value' => $value] = $part;

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
                        case $type === self::EXPR_CLAUSE && $value === $clause:
                            $newGroups[] = $group;
                            $group = [];
                            break;
                        default:
                            $group[] = $part;
                    }
                }

                if (!empty($group)) {
                    $newGroups[] = $group;
                }

                if ($isLastClause) {
                    array_walk($newGroups, fn(array &$one): array => $one = count($one) === 1 ? reset($one) : $one);
                }

                if (count($newGroups) === 1) {
                    $parts = reset($newGroups);
                    $newGroups[] = &$parts;
                    continue;
                }

                $parts = ['type' => self::EXPR_CLAUSE, 'value' => $clause, 'parts' => $newGroups];

                foreach ($parts['parts'] as &$partRef) {
                    $newGroups[] = &$partRef;
                }
            }

            unset($groups);
            $groups = $newGroups;
        }

        return reset($result);
    }
}