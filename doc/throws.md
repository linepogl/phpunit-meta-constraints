# Throws

A constraint to test that a function throws an exception.

```php
self::assertTrows($expected, function(){ $actual });
fn(){ $actual } |> self::trows($expected);
```
