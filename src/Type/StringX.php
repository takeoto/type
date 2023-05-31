<?php

declare(strict_types=1);

namespace Takeoto\Type\Type;

use Takeoto\Type\Contract\TypeX\StringXTypeInterface;
use Takeoto\Type\Type;
use Takeoto\Type\Utility\TypeUtility;

class StringX implements StringXTypeInterface
{
    private string $value;

    /**
     * @param mixed $value
     * @param string|null $errorMessage
     * @phpstan-assert string $value
     * @throws \Throwable
     */
    public function __construct(mixed $value, string $errorMessage = null)
    {
        $this->value = Type::string($value, $errorMessage);
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
    public function string(): string
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function length(int $min, int $max): string
    {
        $length = \strlen($this->value);

        if ($length < $min || $length > $max) {
            TypeUtility::throwWrongTypeException(sprintf(
                'Expected a value to contain between %2$s and %3$s characters. Got: %s',
                $min,
                $max,
                $length,
            ));
        }

        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function pattern(string $pattern): string
    {
        if (!\preg_match($pattern, $this->value)) {
            TypeUtility::throwWrongTypeException(sprintf(
                'The value %s does not match the expected pattern.',
                $pattern,
            ));
        }

        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function lengthMin(int $min): string
    {
        $length = \strlen($this->value);

        if ($length < $min) {
            TypeUtility::throwWrongTypeException(sprintf(
                'Expected a value to contain at least %2$s characters. Got: %s',
                $min,
                $length,
            ));
        }

        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function lengthMax(int $max): string
    {
        $length = \strlen($this->value);

        if ($length < $max) {
            TypeUtility::throwWrongTypeException(sprintf(
                'Expected a value to contain at most %2$s characters. Got: %s',
                $max,
                $length,
            ));
        }

        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function lengthEq(int $than): string
    {
        $length = \strlen($this->value);

        if ($length === $than) {
            TypeUtility::throwWrongTypeException(sprintf(
                'Expected a value to contain %2$s characters. Got: %s',
                $than,
                $length,
            ));
        }

        return $this->value;
    }
}