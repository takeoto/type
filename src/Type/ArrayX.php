<?php

declare(strict_types=1);

namespace Takeoto\Type\Type;

use Takeoto\Type\Contract\ArrayXInterface;
use Takeoto\Type\Contract\MixedXInterface;
use Takeoto\Type\Exception\ArrayXKeyNotFoundException;
use Takeoto\Type\Type;

/**
 * @template TKey of array-key
 * @template TValue
 * @implements ArrayXInterface<TKey, TValue>
 */
class ArrayX implements ArrayXInterface
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
    public function get(int|string $key): MixedXInterface
    {
        if (!$this->has($key)) {
            throw new ArrayXKeyNotFoundException(sprintf('The key "%s" does not exists!', $key));
        }

        return Type::mixedX($this->array[$key]);
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
    public function offsetGet(mixed $offset): MixedXInterface
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
    public function current(): MixedXInterface
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
}