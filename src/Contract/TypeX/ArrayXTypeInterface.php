<?php

declare(strict_types=1);

namespace Takeoto\Type\Contract\TypeX;

use Takeoto\Type\Contract\Type\ArrayTypeInterface;

/**
 * @template TKey of int|string
 * @template TValue
 * @extends \ArrayAccess<TKey, TValue>
 * @extends \Iterator<TKey, TValue>
 */
interface ArrayXTypeInterface extends \ArrayAccess, \Iterator, \Countable, ArrayTypeInterface
{
    /**
     * Gets an array value.
     *
     * @param string|int $key
     * @psalm-param TKey $key
     * @return MixedXTypeInterface
     */
    public function get(string|int $key): MixedXTypeInterface;

    /**
     * Checks the key on existence.
     *
     * @param string|int $key
     * @psalm-param TKey $key
     * @return bool
     */
    public function has(string|int $key): bool;

    /**
     * @param mixed $offset
     * @psalm-param TKey $offset
     * @return MixedXTypeInterface
     */
    public function offsetGet(mixed $offset): MixedXTypeInterface;

    /**
     * @return MixedXTypeInterface
     */
    public function current(): MixedXTypeInterface;
}