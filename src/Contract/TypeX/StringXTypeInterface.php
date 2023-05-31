<?php

declare(strict_types=1);

namespace Takeoto\Type\Contract\TypeX;

use Takeoto\Type\Contract\Type\StringTypeInterface;

interface StringXTypeInterface extends StringTypeInterface
{
    public function length(int $min, int $max): string;
    public function pattern(string $pattern): string;
    public function lengthMin(int $min): string;
    public function lengthMax(int $max): string;
    public function lengthEq(int $than): string;
}