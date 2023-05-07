<?php

declare(strict_types=1);

namespace Takeoto\Type\Contract;

interface ArrayXInterface
{
    /**
     * Gets an array value.
     *
     * @param string|int $key
     * @return MixedXInterface
     */
    public function get(string|int $key): MixedXInterface;

    /**
     * Checks the key on existence.
     *
     * @param string|int $key
     * @return bool
     */
    public function has(string|int $key): bool;
}