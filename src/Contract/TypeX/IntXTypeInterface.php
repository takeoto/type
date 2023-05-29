<?php

declare(strict_types=1);

namespace Takeoto\Type\Contract\TypeX;

use Takeoto\Type\Contract\Type\IntTypeInterface;

interface IntXTypeInterface extends IntTypeInterface
{
    public function range(?int $min = null, ?int $max = null): int;
}