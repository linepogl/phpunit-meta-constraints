# PHPUnit Meta Constraints

A PHPUnit extension with enhanced constraints.


## Installation

```shell
composer require linepogl/phpunit-meta-constraints --dev
```

Optionally, you can include the helper trait in your test cases:

```php
final class MyTestCase extends TestCase
{
    use PhpUnitMetaConstraintsTrait;
    ...
}
```


## Constraints

### Is

A constraint that combines `assertEquals` and `assertSame` into one.

```php
self::assertIs($expected, $actual);
$actual |> self::is($expected);
```

### IsLike

A constraint of constraints.

```php
self::assertIsLike($expected, $actual);
$actual |> self::isLike($expected);
```

### IteratesLike

A constraint to test iterables, without converting them to arrays.

```php
self::assertIteratesLike($expected, $actual);
$actual |> self::iteratesLike($expected, rewind: true);
```

### Throws

A constraint to test that a function throws an exception.

```php
self::assertTrows($expected, function(){ $actual });
fn(){ $actual } |> self::trows($expected);
```
