# Is

A constraint that combines `assertEquals` and `assertSame` into one.

```php
self::assertIs($expected, $actual);
$actual |> self::is($expected);
```

### Why is this constraint useful?

PHP has a very inconsistent behavior around equality comparisons. When comparing scalars, the `===` comparison is suggested. Therefore, checking with `assertSame` is preferred.

```php
self::assertEquals(1, '1'); // passes unexpectedly (false positive)
self::assertSame(1, '1');   // fails as expected
```

When comparing objects, however, the `===` comparison is not the most useful. PHPUnit provides some ways to work around this, by adding equality comparison overloads to the `assertEquals` method.

```php
$a = new DateTimeImmutable('2000-01-01');
$b = new DateTimeImmutable('2000-01-01');
self::assertEquals($a, $b); // passes as expected
self::assertSame($a, $b);   // fails unexpectedly (false negative)
```

As a result, one has to choose:
 - `scalars` => `assertSame`
 - `objects` => `assertEquals`

The question comes up when comparing arrays that contain both scalars and objects. Should we use `assertSame` or `assertEquals`?

```php
self::assertEquals(
    [1, new DateTimeImmutable('2000-01-01')],
    ['1', new DateTimeImmutable('2000-01-01')],
); // passes unexpectedly, because scalars are compared with `==`

self::assertEquals(
    [1, new DateTimeImmutable('2000-01-01')],
    [1, new DateTimeImmutable('2000-01-01')],
); // fails unexpectedly, because objects are compared with `===`
```
The new constraint solves this problem.

```php
$a = new DateTimeImmutable('2000-01-01');
$b = new DateTimeImmutable('2000-01-01');

self::assertIs(1, '1');  // fails as expected
self::assertIs($a, $b);  // passes as expected

self::assertIs([1, $a], ['1', $b]); // fails as expected
self::assertIs([1, $a], [1, $b]);   // passes as expected
```

