# The type
#### Tools for manipulation of data types

```php
use \Takeoto\Type\Type;

# Simple type verification
Type::int(123);
Type::string('someString');
# etc.

$array = [
    'key0' => 'key0_value',
    'key1' => [
        'key1.1' => 12345,
    ],
];

# ArrayX type
Type::arrayX($array)->get('key0123'); # ArrayXKeyNotFound: The key "key0123" does not exist!
Type::arrayX($array)->get('key0')->string(); # "key0_value"        
Type::arrayX($array)->get('key0')->int(); # Expected an integer. Got: string in
Type::arrayX($array)->get('key0')->errorIfNot('Yours custom error message!')->int(); # Yours custom error message!
$arrayX = Type::arrayX($array);
$arrayX['key1']['key1.1']->int(); # 12345

# ObjectX type
class SomeClass {
    public mixed $publicProperty = 'the value of $publicProperty';
    private mixed $privateProperty = 'the value of $privateProperty';
    
    public static function getPrivateProperty(): mixed
    {
        return $this->privateProperty;
    }
}
$object = new SomeClass();
Type::objectX($object)->publicProperty->string(); # "the value of $publicProperty"
Type::objectX($object)->publicProperty->int(); # Error
Type::objectX($object)->getPrivateProperty()->string(); # "the value of $privateProperty"
Type::objectX($object)->getPrivateProperty()->int(); # Error

# Shortcuts
Type::arrayXGet($array, 'key0123');
Type::arrayXGetString($array, 'key0');
Type::arrayXGetStringXLengthMax($array, 'key0', 10);
Type::arrayXGetStringXLength($array, 'key0', 0, 12);
Type::arrayXGetInt($array, 'key0');
Type::arrayXGetErrorIfNotInt($array, 'key0', 'Yours custom error message!');

# Multiple type
Type::arrayXGetStringX($array, 'key0')->lengthMin(5);
Type::arrayXGetStringXlengthMin($array, 'key0', 5);
Type::arrayXGetErrorIfNotNullOrString($array, 'key0', 'Yours custom error message!');
Type::notIntAndNotString(123); 

# IS condition
Type::isNotIntAndNotString([]); # true
Type::arrayXGetIsNotStringOrStringInt([0 => '123'], 0); # true
```