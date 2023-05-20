<?php

namespace Takeoto\Type\Contract\TypeX;

use Takeoto\Type\Contract\Type\StringTypeInterface;

interface StringXTypeInterface extends StringTypeInterface
{
    public function length(?int $min = null, ?int $max = null): string;
    public function pattern(string $pattern): string;
}