<?php

namespace Takeoto\Type;

use Takeoto\Type\Condition\IsCondition;
use Takeoto\Type\Scheme\MethodScheme;

trait ConditionsTrait
{
    public static function is(mixed $value): IsCondition
    {
        return new IsCondition($value);
    }

    public static function isScheme(): MethodScheme
    {
        return MethodScheme::new('is')
            ->arg(0, 'mixed')
            ->return(IsCondition::class);
    }
}