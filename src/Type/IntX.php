<?php

declare(strict_types=1);

namespace Takeoto\Type\Type;

use Takeoto\Type\Contract\TypeX\IntXTypeInterface;
use Takeoto\Type\Type;
use Takeoto\Type\Utility\TypeUtility;

class IntX implements IntXTypeInterface
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
}