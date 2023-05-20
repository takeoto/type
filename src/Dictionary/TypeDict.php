<?php

declare(strict_types=1);

namespace Takeoto\Type\Dictionary;

final class TypeDict
{
    # base
    public const BOOL = 'bool';
    public const INT = 'int';
    public const FLOAT = 'float';
    public const STRING = 'string';
    public const ARRAY = 'array';
    public const OBJECT = 'object';
    public const NULL = 'null';
    public const CALLABLE = 'callable';
    public const MIXED = 'mixed';
    # pseudo
    public const ITERABLE = 'iterable';
    public const NUMERIC = 'numeric';
    public const TRUE = 'true';
    public const FALSE = 'false';
    # custom
    public const STRING_INT = 'stringInt';
    public const EMPTY = 'empty';
}