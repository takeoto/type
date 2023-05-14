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
 * @method static null|callable nullOrCallable()
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
    public function callable(): callable
    {
        return Type::callable($this->value, $this->customErrorMessage);
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
     * @return iterable<array-key,mixed>
     * @throws \Throwable
     */
    public function iterable(): iterable
    {
        return Type::iterable($this->value, $this->customErrorMessage);
    }

    /**
     * @return string|int|float
     * @throws \Throwable
     */
    public function numeric(): string|int|float
    {
        return Type::numeric($this->value, $this->customErrorMessage);
    }

    /**
     * @return bool
     * @throws \Throwable
     */
    public function true(): bool
    {
        return Type::true($this->value, $this->customErrorMessage);
    }

    /**
     * @return bool
     * @throws \Throwable
     */
    public function false(): bool
    {
        return Type::false($this->value, $this->customErrorMessage);
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

    /**
     * @inheritDoc
     */
    public function supportMagicCall(string $method): bool
    {
        return CallUtility::isChainCall(
            $method,
            fn(string $method, int $step, int $steps) => method_exists($this, $method)
                || ($step === $steps && CallUtility::isStrictTypeCall($method))
        );
    }

    /**
     * @inheritDoc
     */
    public function __call(string $method, array $arguments): mixed
    {
        if (!$this->supportMagicCall($method)) {
            throw new \RuntimeException(sprintf('Method "%s" does not exist.', $method));
        }

        if (CallUtility::isStrictTypeCall($method)) {
            return CallUtility::strictTypeCall($method, [$this->value, $this->customErrorMessage]);
        }

        return CallUtility::callChain($method, $arguments, $this, fn(string $m) => method_exists($this, $m)
            || CallUtility::isStrictTypeCall($m)
        );
    }
}