<?php

declare(strict_types=1);

namespace Takeoto\Type\Contract;

/**
 * @template TKey of int|string
 * @template TValue
 * @extends \ArrayAccess<TKey, TValue>
 * @extends \Iterator<TKey, TValue>
 */
interface ArrayXInterface extends \ArrayAccess, \Iterator, \Countable
{
    /**
     * Gets an array value.
     *
     * @param string|int $key
     * @psalm-param TKey $key
     * @return MixedXInterface
     */
    public function get(string|int $key): MixedXInterface;

    /**
     * Checks the key on existence.
     *
     * @param string|int $key
     * @psalm-param TKey $key
     * @return bool
     */
    public function has(string|int $key): bool;

    /**
     * Returns an original array.
     *
     * @return array<TKey,TValue>
     */
    public function array(): array;

    /**
     * @param mixed $offset
     * @psalm-param TKey $offset
     * @return MixedXInterface
     */
    public function offsetGet(mixed $offset): MixedXInterface;

    /**
     * @return MixedXInterface
     */
    public function current(): MixedXInterface;
}