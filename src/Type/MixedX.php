<?php

declare(strict_types=1);

namespace Takeoto\Type\Type;

use Takeoto\Type\Condition\ErrorIfCondition;
use Takeoto\Type\Contract\MagicCallableInterface;
use Takeoto\Type\Contract\Scheme\MethodSchemeInterface;
use Takeoto\Type\Contract\TransitionalInterface;
use Takeoto\Type\Contract\TypeX\IntXTypeInterface;
use Takeoto\Type\Contract\TypeX\MixedXTypeInterface;
use Takeoto\Type\Contract\TypeX\StringXTypeInterface;
use Takeoto\Type\Scheme\MethodScheme;
use Takeoto\Type\Type;
use Takeoto\Type\Utility\CallUtility;

/**
 * NOT types
 *
 * @method mixed notEmpty()
 * @method mixed notFalse()
 * @method mixed notTrue()
 * @method float|string|object|array|bool|callable|null|iterable notInt()
 * @method int|string|object|array|bool|callable|null|iterable notFloat()
 * @method int|float|object|array|bool|callable|null|iterable notString()
 * @method int|float|string|array|bool|callable|null|iterable notObject()
 * @method int|float|string|object|bool|callable|null|iterable notArray()
 * @method int|float|string|object|array|callable|null|iterable notBool()
 * @method int|float|string|object|array|bool|null|iterable notCallable()
 * @method int|float|string|object|array|bool|callable|iterable notNull()
 *
 * Multiple types
 *
 * @method null|int nullOrInt()
 * @method null|float nullOrFloat()
 * @method null|string nullOrString()
 * @method null|object nullOrObject()
 * @method null|array nullOrArray()
 * @method null|bool nullOrBool()
 * @method null|callable nullOrCallable()
 * @method null|string nullOrStringInt()
 * @method null|iterable nullOrIterable()
 * @method null|string|int|float nullOrNumeric()
 * @method int|string intOrStringInt()
 */
class MixedX implements MixedXTypeInterface, TransitionalInterface, MagicCallableInterface
{
    public function __construct(private mixed $value)
    {
    }

    /**
     * @param mixed $value
     * @return self
     */
    public static function new(mixed $value): self
    {
        return new self($value);
    }

    /**
     * @inheritDoc
     */
    public function int(): int
    {
        return Type::int($this->value);
    }

    /**
     * @inheritDoc
     */
    public function float(): float
    {
        return Type::float($this->value);
    }

    /**
     * @inheritDoc
     */
    public function string(): string
    {
        return Type::string($this->value);
    }

    /**
     * @inheritDoc
     */
    public function object(): object
    {
        return Type::object($this->value);
    }

    /**
     * @inheritDoc
     */
    public function array(): array
    {
        return Type::array($this->value);
    }

    /**
     * @inheritDoc
     */
    public function bool(): bool
    {
        return Type::bool($this->value);
    }

    /**
     * @inheritDoc
     */
    public function callable(): callable
    {
        return Type::callable($this->value);
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
        return Type::null($this->value);
    }

    /**
     * @return ObjectX
     * @throws \Throwable
     */
    public function objectX(): ObjectX
    {
        return ObjectX::new($this->value);
    }

    /**
     * @return ArrayX<int|string,mixed>
     * @throws \Throwable
     */
    public function arrayX(): ArrayX
    {
        return ArrayX::new($this->value);
    }

    /**
     * The scheme for self::arrayX.
     *
     * @return MethodSchemeInterface
     */
    public static function arrayXScheme(): MethodSchemeInterface
    {
        return MethodScheme::new('arrayX')
            ->return(ArrayX::class);
    }

    /**
     * @inheritDoc
     */
    public function stringX(): StringX
    {
        return StringX::new($this->value);
    }

    /**
     * The scheme for self::stringX.
     *
     * @return MethodSchemeInterface
     */
    public static function stringXScheme(): MethodSchemeInterface
    {
        return MethodScheme::new('stringX')
            ->return(StringX::class);
    }

    /**
     * @inheritDoc
     */
    public function intX(): IntXTypeInterface
    {
        return IntX::new($this->value);
    }

    /**
     * The scheme for self::intX.
     *
     * @return MethodSchemeInterface
     */
    public static function intXScheme(): MethodSchemeInterface
    {
        return MethodScheme::new('intX')
            ->return(IntX::class);
    }

    /**
     * @return iterable<array-key,mixed>
     * @throws \Throwable
     */
    public function iterable(): iterable
    {
        return Type::iterable($this->value);
    }

    /**
     * @return string|int|float
     * @throws \Throwable
     */
    public function numeric(): string|int|float
    {
        return Type::numeric($this->value);
    }

    /**
     * @return bool
     * @throws \Throwable
     */
    public function true(): bool
    {
        return Type::true($this->value);
    }

    /**
     * @return bool
     * @throws \Throwable
     */
    public function false(): bool
    {
        return Type::false($this->value);
    }

    /**
     * @param string $message
     * @return ErrorIfCondition
     */
    public function errorIf(string $message): ErrorIfCondition
    {
        return new ErrorIfCondition($this->value, $message);
    }

    /**
     * The scheme for self::errorIf.
     *
     * @return MethodSchemeInterface
     */
    public static function errorIfScheme(): MethodSchemeInterface
    {
        return MethodScheme::new('errorIf')
            ->arg(0, 'string')
            ->return(ErrorIfCondition::class);
    }

    /**
     * @inheritDoc
     */
    public function supportMagicCall(string $method): bool
    {
        return CallUtility::isTypeExpressionCall($method) || CallUtility::isTransitCall($method, $this);
    }

    /**
     * @inheritDoc
     */
    public function __call(string $method, array $arguments): mixed
    {
        if (!$this->supportMagicCall($method)) {
            throw new \RuntimeException(sprintf('Method "%s" does not exist.', $method));
        }

        if (CallUtility::isTypeExpressionCall($method)) {
            return CallUtility::strictTypeCall($method, [$this->value]);
        }

        return CallUtility::callTransit($method, $arguments, $this);
    }
    /**
     * @inheritDoc
     */
    public static function getMethodScheme(string $method): ?MethodSchemeInterface
    {
        return CallUtility::isTypeExpressionCall($method)
            ? MethodScheme::new($method)->return(CallUtility::typeExpressionCallToType($method))
            : CallUtility::getSelfMethodSchema($method, static::class);
    }
}