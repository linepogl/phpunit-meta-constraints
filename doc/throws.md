# Throws

A constraint to test that a function throws an exception.

```php
self::assertThrows($expected, function(){ $actual });
function() { $actual } |> self::throws($expected);
```
