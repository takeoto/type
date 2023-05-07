<?php

declare(strict_types=1);

namespace Takeoto\Type;

use Takeoto\Type\Type\ArrayX;

class Test
{
    public function call(): void
    {
        $array = [
            'key0' => 'key0_value',
            'key1' => [
                'key1.1' => 12345
            ],
        ];

        # "key0_value"
        $value = ArrayX::new($array)->get('key0')->array();
        var_dump($value);
    }
}