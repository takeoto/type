# type
Tools of types manipulating

```php
use \Takeoto\Strict\Contract\NullOr;

Type::array($array, true)->get('key')->nullOrInt();

Type::array($array)->get('key')->errorIfNot('The value should be an int.')->int();

Type::array($array);


# v2
Type::arrayGetInt($array, 'key');
```