<?php

declare(strict_types=1);

namespace Takeoto\Type\Type;

use Takeoto\Type\Contract\Scheme\MethodSchemeInterface;
use Takeoto\Type\Contract\TransitionalInterface;
use Takeoto\Type\Contract\TypeX\IntXTypeInterface;
use Takeoto\Type\Scheme\MethodScheme;
use Takeoto\Type\Type;
use Takeoto\Type\Utility\CallUtility;
use Takeoto\Type\Utility\TypeUtility;

class IntX implements IntXTypeInterface, TransitionalInterface
{
    private int $value;

    /**
     * @param mixed $value
     * @param string|null $errorMessage
     * @phpstan-assert int $value
     * @throws \Throwable
     */
    public function __construct(mixed $value, string $errorMessage = null)
    {
        $this->value = Type::int($value, $errorMessage);
    }

    /**
     * @param mixed $value
     * @param string|null $errorMessage
     * @return self
     * @throws \Throwable
     */
    public static function new(mixed $value, string $errorMessage = null): self
    {
        return new self($value, $errorMessage);
    }

    /**
     * @inheritDoc
     */
    public function int(): int
    {
        return $this->value;
    }

    /**
     * The scheme for self::int.
     *
     * @return MethodSchemeInterface
     */
    public static function intScheme(): MethodSchemeInterface
    {
        return MethodScheme::new('int')
            ->return('int');
    }

    /**
     * @inheritDoc
     */
    public function range(int $min, int $max): int
    {
        if ($this->value < $min || $this->value > $max) {
            TypeUtility::throwWrongTypeException(sprintf(
                'Expected a value between %2$s and %3$s. Got: %s',
                $min,
                $max,
                $this->value,
            ));
        }

        return $this->value;
    }

    /**
     * The scheme for self::range.
     *
     * @return MethodSchemeInterface
     */
    public static function rangeScheme(): MethodSchemeInterface
    {
        return MethodScheme::new('range')
            ->arg(0, 'int')
            ->arg(1, 'int')
            ->return('int');
    }

    /**
     * @inheritDoc
     */
    public function greater(int $than): int
    {
        if ($this->value <= $than) {
            TypeUtility::throwWrongTypeException(sprintf(
                'Expected a value greater than %2$s. Got: %s',
                $than,
                $this->value,
            ));
        }

        return $this->value;
    }

    /**
     * The scheme for self::greater.
     *
     * @return MethodSchemeInterface
     */
    public static function greaterScheme(): MethodSchemeInterface
    {
        return MethodScheme::new('greater')
            ->arg(0, 'int')
            ->return('int');
    }

    /**
     * @inheritDoc
     */
    public function less(int $than): int
    {
        if ($this->value >= $than) {
            TypeUtility::throwWrongTypeException(sprintf(
                'Expected a value less than %2$s. Got: %s',
                $than,
                $this->value,
            ));
        }

        return $this->value;
    }

    /**
     * The scheme for self::less.
     *
     * @return MethodSchemeInterface
     */
    public static function lessScheme(): MethodSchemeInterface
    {
        return MethodScheme::new('less')
            ->arg(0, 'int')
            ->return('int');
    }

    /**
     * @inheritDoc
     */
    public function greaterOrEq(int $than): int
    {
        if ($this->value < $than) {
            TypeUtility::throwWrongTypeException(sprintf(
                'Expected a value greater or equal than %2$s. Got: %s',
                $than,
                $this->value,
            ));
        }

        return $this->value;
    }

    /**
     * The scheme for self::greaterOrEq.
     *
     * @return MethodSchemeInterface
     */
    public static function greaterOrEqScheme(): MethodSchemeInterface
    {
        return MethodScheme::new('greaterOrEq')
            ->arg(0, 'int')
            ->return('int');
    }

    /**
     * @inheritDoc
     */
    public function lessOrEq(int $than): int
    {
        if ($this->value > $than) {
            TypeUtility::throwWrongTypeException(sprintf(
                'Expected a value less or equal than %2$s. Got: %s',
                $than,
                $this->value,
            ));
        }

        return $this->value;
    }

    /**
     * The scheme for self::lessOrEq.
     *
     * @return MethodSchemeInterface
     */
    public static function lessOrEqScheme(): MethodSchemeInterface
    {
        return MethodScheme::new('lessOrEq')
            ->arg(0, 'int')
            ->return('int');
    }

    /**
     * @inheritDoc
     */
    public function eq(int $than): int
    {
        if ($this->value !== $than) {
            TypeUtility::throwWrongTypeException(sprintf(
                'Expected a value equal to %2$s. Got: %s',
                $than,
                $this->value,
            ));
        }

        return $this->value;
    }

    /**
     * The scheme for self::eq.
     *
     * @return MethodSchemeInterface
     */
    public static function eqScheme(): MethodSchemeInterface
    {
        return MethodScheme::new('eq')
            ->arg(0, 'int')
            ->return('int');
    }

    /**
     * @inheritDoc
     */
    public static function getMethodScheme(string $method): ?MethodSchemeInterface
    {
        return CallUtility::getSelfMethodSchema($method, static::class);
    }
}