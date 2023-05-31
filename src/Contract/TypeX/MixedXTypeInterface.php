<?php

declare(strict_types=1);

namespace Takeoto\Type\Contract\TypeX;

use Takeoto\Type\Contract\Type\MixedTypeInterface;

interface MixedXTypeInterface extends MixedTypeInterface
{
    public function stringX(): StringXTypeInterface;
    public function arrayX(): ArrayXTypeInterface;
    public function objectX(): ObjectXTypeInterface;
    public function intX(): IntXTypeInterface;
}
