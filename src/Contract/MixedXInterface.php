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
     * @param bool $asObjectX
     * @return ($asObjectX is true ? ObjectXInterface : object)
     * @throws \Throwable
     */
    public function object(bool $asObjectX = false): object;

    /**
     * @param bool $asArrayX
     * @return ($asArrayX is true ? ArrayXInterface<int|string,mixed> : array<int|string,mixed>)
     * @throws \Throwable
     */
    public function array(bool $asArrayX = false): array|ArrayXInterface;

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
