<?php

declare(strict_types=1);

namespace Takeoto\Type\Type;

use Takeoto\Type\Contract\ArrayXInterface;
use Takeoto\Type\Contract\MixedXInterface;
use Takeoto\Type\Type;
use Takeoto\Type\Utility\TypeUtility;

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
    public function object(): object
    {
        return Type::object($this->value, $this->customErrorMessage);
    }

    /**
     * @inheritDoc
     */
    public function objectX(): object
    {
        return ObjectX::new($this->value, $this->customErrorMessage);
    }

    /**
     * @inheritDoc
     */
    public function array(): array
    {
        return Type::array($this->value, $this->customErrorMessage);
    }

    public function arrayX(): ArrayXInterface
    {
        return ArrayX::new($this->value, $this->customErrorMessage);
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

    public function __call(string $method, array $arguments): mixed
    {
        $types = TypeUtility::normalizeType($method);

        if (count($types) === 1) {
            throw new \Exception('Method does not exist: ' . $method);
        }

        foreach ($types as $type) {
            if (TypeUtility::verifyType($this->value, $type)) {
                return $this->value;
            }
        }

        TypeUtility::throwWrongTypeException(\sprintf(
            $this->customErrorMessage ?? 'The value should be one of types %s. Got: %s',
            implode('|', $types),
            TypeUtility::typeToString($this->value),
        ));
    }


}