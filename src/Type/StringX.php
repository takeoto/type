<?php

declare(strict_types=1);

namespace Takeoto\Type\Type;

use Takeoto\Type\Contract\Scheme\MethodSchemeInterface;
use Takeoto\Type\Contract\TransitionalInterface;
use Takeoto\Type\Contract\TypeX\StringXTypeInterface;
use Takeoto\Type\Scheme\MethodScheme;
use Takeoto\Type\Type;
use Takeoto\Type\Utility\CallUtility;
use Takeoto\Type\Utility\TypeUtility;

class StringX implements StringXTypeInterface, TransitionalInterface
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
     * The scheme for self::string.
     *
     * @return MethodSchemeInterface
     */
    public static function stringScheme(): MethodSchemeInterface
    {
        return MethodScheme::new('string')
            ->return('string');
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
     * The scheme for self::length.
     *
     * @return MethodSchemeInterface
     */
    public static function lengthScheme(): MethodSchemeInterface
    {
        return MethodScheme::new('length')
            ->arg(0, 'int')
            ->arg(1, 'int')
            ->return('string');
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
     * The scheme for self::pattern.
     *
     * @return MethodSchemeInterface
     */
    public static function patternScheme(): MethodSchemeInterface
    {
        return MethodScheme::new('pattern')
            ->arg(0, 'string')
            ->return('string');
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
     * The scheme for self::lengthMin.
     *
     * @return MethodSchemeInterface
     */
    public static function lengthMinScheme(): MethodSchemeInterface
    {
        return MethodScheme::new('lengthMin')
            ->arg(0, 'int')
            ->return('string');
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
     * The scheme for self::lengthMax.
     *
     * @return MethodSchemeInterface
     */
    public static function lengthMaxScheme(): MethodSchemeInterface
    {
        return MethodScheme::new('lengthMax')
            ->arg(0, 'int')
            ->return('string');
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

    /**
     * The scheme for self::lengthEq.
     *
     * @return MethodSchemeInterface
     */
    public static function lengthEqScheme(): MethodSchemeInterface
    {
        return MethodScheme::new('lengthEq')
            ->arg(0, 'int')
            ->return('string');
    }

    public static function getMethodScheme(string $method): ?MethodSchemeInterface
    {
        return CallUtility::getSelfMethodSchema($method, static::class);
    }
}