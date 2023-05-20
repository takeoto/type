<?php

declare(strict_types=1);

namespace Takeoto\Type\Type;

use Takeoto\Type\Contract\MagicCallableInterface;
use Takeoto\Type\Contract\TransitionalInterface;
use Takeoto\Type\Contract\TypeX\MixedXInterface;
use Takeoto\Type\Dictionary\SchemeDict;
use Takeoto\Type\Dictionary\TypeDict;
use Takeoto\Type\Type;
use Takeoto\Type\Utility\CallUtility;
use Takeoto\Type\Utility\TypeUtility;

/**
 * NOT types
 *
 * @method static mixed notEmpty()
 * @method static mixed notFalse()
 * @method static mixed notTrue()
 * @method static float|string|object|array|bool|callable|null|iterable notInt()
 * @method static int|string|object|array|bool|callable|null|iterable notFloat()
 * @method static int|float|object|array|bool|callable|null|iterable notString()
 * @method static int|float|string|array|bool|callable|null|iterable notObject()
 * @method static int|float|string|object|bool|callable|null|iterable notArray()
 * @method static int|float|string|object|array|callable|null|iterable notBool()
 * @method static int|float|string|object|array|bool|null|iterable notCallable()
 * @method static int|float|string|object|array|bool|callable|iterable notNull()
 *
 * Multiple types
 *
 * @method static null|int nullOrInt()
 * @method static null|float nullOrFloat()
 * @method static null|string nullOrString()
 * @method static null|object nullOrObject()
 * @method static null|array nullOrArray()
 * @method static null|bool nullOrBool()
 * @method static null|callable nullOrCallable()
 * @method static null|string nullOrStringInt()
 * @method static null|iterable nullOrIterable()
 * @method static null|string|int|float nullOrNumeric()
 * @method static int|string intOrStringInt()
 */
class MixedX implements MixedXInterface, MagicCallableInterface, TransitionalInterface
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
     * The scheme for self::errorIfNot.
     *
     * @return mixed[]
     */
    public static function errorIfNotScheme(): array
    {
        return [
            SchemeDict::ARGUMENTS => [
                [
                    SchemeDict::TYPE => TypeUtility::oneOf(TypeDict::STRING, TypeDict::NULL),
                ],
            ],
            SchemeDict::RETURN => static::class,
        ];
    }

    /**
     * @inheritDoc
     */
    public function supportMagicCall(string $method): bool
    {
        return CallUtility::isStrictTypeCall($method) || CallUtility::isTransitCall($method, $this);
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

        return CallUtility::callTransit($method, $arguments, $this);
    }

    public static function parseTransitMethod(string $method): ?string
    {
        return CallUtility::parseMethod(
            $method,
            static::class,
            fn(string $method): bool => static::getTransitMethodScheme($method) !== null
        );
    }

    public static function getTransitMethodScheme(string $method): ?array
    {
        return CallUtility::isStrictTypeCall($method) ? [
            SchemeDict::ARGUMENTS => [],
            SchemeDict::RETURN => TypeUtility::oneOf(...array_column(
                iterator_to_array(CallUtility::iterateMethodTypes($method)),
                'type',
            )),
        ] : CallUtility::getSelfMethodSchema($method, static::class);
    }
}