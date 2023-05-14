<?php

declare(strict_types=1);

namespace Takeoto\Type\Type;

use Takeoto\Type\Contract\ArrayXInterface;
use Takeoto\Type\Contract\MagicCallableInterface;
use Takeoto\Type\Exception\ArrayXKeyNotFoundException;
use Takeoto\Type\Type;
use Takeoto\Type\Utility\CallUtility;

/**
 * @template TKey of array-key
 * @template TValue
 * @implements ArrayXInterface<TKey, TValue>
 */
class ArrayX implements ArrayXInterface, MagicCallableInterface
{
    /**
     * @var array<TKey,TValue>
     */
    private array $array;

    /**
     * @param mixed $array
     * @param string|null $errorMessage
     * @phpstan-assert array<TKey,TValue> $array
     * @throws \Throwable
     */
    public function __construct(mixed $array, string $errorMessage = null)
    {
        $this->array = Type::array($array, $errorMessage);
    }

    /**
     * @param mixed $array
     * @param string|null $errorMessage
     * @return self<TKey,TValue>
     * @throws \Throwable
     */
    public static function new(mixed $array, string $errorMessage = null): self
    {
        return new self($array, $errorMessage);
    }

    /**
     * @inheritDoc
     */
    public function get(int|string $key): MixedX
    {
        if (!$this->has($key)) {
            throw new ArrayXKeyNotFoundException(sprintf('The key "%s" does not exists!', $key));
        }

        return MixedX::new($this->array[$key]);
    }

    /**
     * @inheritDoc
     */
    public function has(int|string $key): bool
    {
        return array_key_exists($key, $this->array);
    }

    /**
     * @inheritDoc
     */
    public function array(): array
    {
        return $this->array;
    }

    /**
     * @inheritDoc
     * @throws \Throwable
     */
    public function offsetGet(mixed $offset): MixedX
    {
        return $this->get($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === null) {
            $this->array[] = $value;
        } else {
            $this->array[$offset] = $value;
        }
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->array);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset): void
    {
        unset($this->array[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->array);
    }

    /**
     * @inheritDoc
     */
    public function current(): MixedX
    {
        return Type::mixedX(current($this->array));
    }

    /**
     * @inheritDoc
     */
    public function next(): void
    {
        next($this->array);
    }

    /**
     * @return TKey|null
     */
    public function key(): mixed
    {
        return key($this->array);
    }

    /**
     * @inheritDoc
     */
    public function valid(): bool
    {
        return key($this->array) !== null;
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        reset($this->array);
    }

    public function supportMagicCall(string $method): bool
    {
        return CallUtility::isTransitCall($method, $this, fn(string $method) => in_array($method, ['get', 'current']));
    }

    public function __call(string $method, array $arguments): mixed
    {
        if (!$this->supportMagicCall($method)) {
            throw new \RuntimeException(sprintf('Method "%s" does not exist.', $method));
        }

        return CallUtility::callTransit($method, $arguments, $this);
    }
}