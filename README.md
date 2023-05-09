# The type
#### Tools for manipulation of data types

```php
use \Takeoto\Strict\Contract\NullOr;
use \Takeoto\Strict\Type\ArrayX;
$shouldBeInt = NullOr::int('some string'); # throws the exception
$shouldBeInt = NullOr::int(123); # assigns 123 number
$shouldBeInt = NullOr::int(null); # assigns NULL number

$array = [
    'key0' => 'key0_value',
    'key1' => [
        'key1.1' => 12345
    ],
];

# Uncaught Takeoto\Type\Exception\ArrayXKeyNotFound: The key "key0123" does not exists!
Type::arrayX($array)->get('key0123');
Type::arrayX($array)->get('key0')->string(); # "key0_value"        
Type::arrayX($array)->get('key0')->int(); # Expected an integer. Got: string in
Type::arrayX($array)->get('key0')->errorIfNot('Yours custom error message!')->int(); # Yours custom error message!

Type::arrayXGetInt($array);
Type::arrayXGetErrorIfNotInt($array);
```