# IsLike

A constraint of constraints.

```php
self::assertIsLike($expected, $actual);
$actual |> self::isLike($expected);
```

### Why is this constraint useful?

Just an example will give you an idea

```php
$actual |> self::isLike([

    // No need to inject uuid generator services
    'id' => self::isInstanceOf(Uuid::class),

    // Plain values be converted to self::is('John')
    'name' => 'John', 

    // Nested arrays will be recursively converted to self::isLike([...])
    'address' => [
        'street' => 'Main Street',
        'city' => 'New York',
        'zip' => 10001,
    ],

    // Any kind of constraint can be used
    'age' => self::greaterThan(18),
    
    // ExtBy default, we don't care if the array contains more attributes. Here is
    // how to require that an attribute should not be present:
    'height' => self::isUndefined(),

]);
```
