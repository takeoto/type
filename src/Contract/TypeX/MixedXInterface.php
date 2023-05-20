<?php

declare(strict_types=1);

namespace Takeoto\Type\Contract\TypeX;

use Takeoto\Type\Contract\Type\ArrayTypeInterface;
use Takeoto\Type\Contract\Type\BoolTypeInterface;
use Takeoto\Type\Contract\Type\CallableTypeInterface;
use Takeoto\Type\Contract\Type\FloatTypeInterface;
use Takeoto\Type\Contract\Type\IntTypeInterface;
use Takeoto\Type\Contract\Type\MixedTypeInterface;
use Takeoto\Type\Contract\Type\NullTypeInterface;
use Takeoto\Type\Contract\Type\ObjectTypeInterface;
use Takeoto\Type\Contract\Type\StringTypeInterface;

interface MixedXInterface extends
    IntTypeInterface,
    FloatTypeInterface,
    StringTypeInterface,
    ObjectTypeInterface,
    ArrayTypeInterface,
    BoolTypeInterface,
    CallableTypeInterface,
    MixedTypeInterface,
    NullTypeInterface
{
}
