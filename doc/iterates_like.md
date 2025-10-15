# IteratesLike

A constraint to test iterables, without converting them to arrays.

```php
self::assertIteratesLike($expected, $actual);
$actual |> self::iteratesLike($expected, rewind: true);
```
