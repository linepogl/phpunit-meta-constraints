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


### Constraints refererce

- [Is](doc/is.md)
- [IsLike](doc/is_like.md)
- [IteratesLike](doc/iterates_like.md)
- [Throws](doc/throws.md)
