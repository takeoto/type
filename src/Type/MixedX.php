<?php

declare(strict_types=1);

namespace Takeoto\Type\Type;

use Takeoto\Type\Contract\MixedXInterface;
use Takeoto\Type\Contract\MagicCallableInterface;
use Takeoto\Type\Type;
use Takeoto\Type\Utility\CallUtility;

/**
 * @method static null|int nullOrInt()
 * @method static null|float nullOrFloat()
 * @method static null|string nullOrString()
 * @method static null|object nullOrObject()
 * @method static null|array nullOrArray()
 * @method static null|bool nullOrBool()
 */
class MixedX implements MixedXInterface, MagicCallableInterface
{
    private ?string $customErrorMessage = null;

    public function __construct(private mixed $value)
    {
    }

    public static function new(mixed $value): self
    {
        return new self($value);
    }

    /**
     * @inheritDoc
     */
    public function int(): int
    {
        return Type::int($this->value, $this->customErrorMessage);
    }

    /**
     * @inheritDoc
     */
    public function float(): float
    {
        return Type::float($this->value, $this->customErrorMessage);
    }

    /**
     * @inheritDoc
     */
    public function string(): string
    {
        return Type::string($this->value, $this->customErrorMessage);
    }

    /**
     * @inheritDoc
     */
    public function object(): object
    {
        return Type::object($this->value, $this->customErrorMessage);
    }

    /**
     * @inheritDoc
     */
    public function array(): array
    {
        return Type::array($this->value, $this->customErrorMessage);
    }

    /**
     * @inheritDoc
     */
    public function bool(): bool
    {
        return Type::bool($this->value, $this->customErrorMessage);
    }

    /**
     * @inheritDoc
     */
    public function mixed(): mixed
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function null()
    {
        return Type::null($this->value, $this->customErrorMessage);
    }

    /**
     * @inheritDoc
     */
    public function __call(string $method, array $arguments): mixed
    {
        return CallUtility::strictTypeCall($method, [$this->value, $this->customErrorMessage]);
    }

    /**
     * @inheritDoc
     */
    public function supportMagicCall(string $method, array $arguments): bool
    {
        return CallUtility::isStrictTypeCall($method, [$this->value, $this->customErrorMessage]);
    }

    /**
     * @return ObjectX
     * @throws \Throwable
     */
    public function objectX(): ObjectX
    {
        return ObjectX::new($this->value, $this->customErrorMessage);
    }

    /**
     * @return ArrayX<int|string,mixed>
     * @throws \Throwable
     */
    public function arrayX(): ArrayX
    {
        return ArrayX::new($this->value, $this->customErrorMessage);
    }

    /**
     * @param string|null $message
     * @return static
     */
    public function errorIfNot(?string $message): static
    {
        $this->customErrorMessage = $message;

        return $this;
    }
}