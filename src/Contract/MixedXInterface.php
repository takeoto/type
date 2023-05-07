<?php

declare(strict_types=1);

namespace Takeoto\Type\Contract;

interface MixedXInterface
{
    /**
     * @return int
     */
    public function int(): int;

    /**
     * @return float
     */
    public function float(): float;

    /**
     * @return string
     */
    public function string(): string;

    /**
     * @param bool $asObjectX
     * @return ($asObjectX is true ? ObjectXInterface : object)
     */
    public function object(bool $asObjectX = false): object;

    /**
     * @param bool $asArrayX
     * @return ($asArrayX is true ? ArrayXInterface : mixed[])
     */
    public function array(bool $asArrayX = false): array|ArrayXInterface;

    /**
     * @throws \Throwable
     * @return bool
     */
    public function bool(): bool;

    /**
     * @throws \Throwable
     * @return mixed
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
