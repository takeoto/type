<?php

declare(strict_types=1);

namespace Takeoto\Type\Contract\TypeX;

use Takeoto\Type\Contract\Type\IntTypeInterface;

interface IntXTypeInterface extends IntTypeInterface
{
    public function range(int $min, int $max): int;
    public function greater(int $than): int;
    public function less(int $than): int;
    public function greaterOrEq(int $than): int;
    public function lessOrEq(int $than): int;
    public function eq(int $than): int;
}