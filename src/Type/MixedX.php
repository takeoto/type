<?php

declare(strict_types=1);

namespace Takeoto\Type\Type;

use Takeoto\Type\Contract\ArrayXInterface;
use Takeoto\Type\Contract\MixedXInterface;
use Takeoto\Type\Type;

class MixedX implements MixedXInterface
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
    public function object(bool $asObjectX = false): object
    {
        return $asObjectX
            ? ObjectX::new($this->value, $this->customErrorMessage)
            : Type::object($this->value, $this->customErrorMessage);
    }

    /**
     * @param bool $asArrayX
     * @return ($asArrayX is true ? ArrayXInterface<int|string,mixed> : array<int|string,mixed>)
     * @throws \Throwable
     */
    public function array(bool $asArrayX = false): array|ArrayXInterface
    {
        return $asArrayX
            ? ArrayX::new($this->value, $this->customErrorMessage)
            : Type::array($this->value, $this->customErrorMessage);
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

    public function errorIfNot(?string $message): static
    {
        $this->customErrorMessage = $message;

        return $this;
    }
}