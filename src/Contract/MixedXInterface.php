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
     * @return ObjectXInterface
     * @throws \Throwable
     */
    public function object(): object;

    /**
     * @return array<int|string,mixed>
     * @throws \Throwable
     */
    public function array(): array;

    /**
     * @return ObjectXInterface
     * @throws \Throwable
     */
    public function objectX(): object;

    /**
     * @return ArrayXInterface<int|string,mixed>
     * @throws \Throwable
     */
    public function arrayX(): ArrayXInterface;

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

    /**
     * @param string|null $message
     * @return static
     */
    public function errorIfNot(?string $message): static;
}
