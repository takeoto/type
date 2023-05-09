<?php

declare(strict_types=1);

namespace Takeoto\Type\Contract;

interface MixedXInterface
{
    /**
     * @return int
     * @throws \Throwable
     */
    public function int(): int;

    /**
     * @return float
     * @throws \Throwable
     */
    public function float(): float;

    /**
     * @return string
     * @throws \Throwable
     */
    public function string(): string;

    /**
     * @return object
     * @throws \Throwable
     */
    public function object(): object;

    /**
     * @return array<int|string,mixed>
     * @throws \Throwable
     */
    public function array(): array;

    /**
     * @return bool
     * @throws \Throwable
     */
    public function bool(): bool;

    /**
     * @return mixed
     * @throws \Throwable
     */
    public function mixed(): mixed;

    /**
     * @return null
     * @throws \Throwable
     */
    public function null();
}
