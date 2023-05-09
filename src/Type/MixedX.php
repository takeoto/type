<?php

declare(strict_types=1);

namespace Takeoto\Type\Type;

use Takeoto\Type\Contract\ArrayXInterface;
use Takeoto\Type\Contract\MixedXInterface;
use Takeoto\Type\Contract\ObjectXInterface;
use Takeoto\Type\Contract\PredictableMagicCallInterface;
use Takeoto\Type\Type;
use Takeoto\Type\Utility\CallUtility;
use Takeoto\Type\Utility\TypeUtility;

/**
 * @method static null|int nullOrInt()
 * @method static null|float nullOrFloat()
 * @method static null|string nullOrString()
 * @method static null|object nullOrObject()
 * @method static null|array nullOrArray()
 * @method static null|bool nullOrBool()
 */
class MixedX implements MixedXInterface, PredictableMagicCallInterface
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
    public function objectX(): ObjectXInterface
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

    /**
     * @inheritDoc
     */
    public function __call(string $method, array $arguments): mixed
    {
        $types = preg_split('/(?<!^|[A-Z^])Or(?=[A-Z])/', $method);

        if ($types === false || count($types) === 1) {
            throw new \Exception('Method does not exist: ' . $method);
        }

        foreach ($types as &$type) {
            if (TypeUtility::verifyType($this->value, $type = strtolower($type))) {
                return $this->value;
            }
        }

        TypeUtility::throwWrongTypeException(\sprintf(
            $this->customErrorMessage ?? 'The value should be one of types %s. Got: %s',
            implode('|', $types),
            TypeUtility::typeToString($this->value),
        ));
    }

    /**
     * @inheritDoc
     */
    public function supportMagicCall(string $method, array $arguments): bool
    {
        $methodParts = CallUtility::parseMethod($method);
        $shouldBeType = true;

        for ($i = count($methodParts) - 1; $i > 0; $i--) {
            $part = $methodParts[$i];
            $isTypeCall = $shouldBeType ? TypeUtility::hasType(strtolower($part)) : $part === 'Or';
            $shouldBeType = !$shouldBeType;

            if (!$isTypeCall) {
                return false;
            }
        }

        return true;
    }
}