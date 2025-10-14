<?php

declare(strict_types=1);

namespace PHPUnitMetaConstraints\Util;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnitMetaConstraints\Is;
use PHPUnitMetaConstraints\IsLike;
use PHPUnitMetaConstraints\IsUndefined;
use PHPUnitMetaConstraints\IteratesLike;
use PHPUnitMetaConstraints\Throws;
use Throwable;

/**
 * @phpstan-require-extends Assert
 */
trait PhpUnitMetaConstraintsTrait
{
    final public static function is(mixed $expected): Is
    {
        return new Is($expected);
    }

    public static function assertIs(mixed $expected, mixed $actual, string $messsage = ''): void
    {
        Assert::assertThat($actual, new Is($expected), $messsage);
    }

    public static function assertIsNot(mixed $expected, mixed $actual, string $messsage = ''): void
    {
        Assert::assertThat($actual, new LogicalNot(new Is($expected)), $messsage);
    }

    final public static function isLike(mixed $expected): IsLike
    {
        return new IsLike($expected);
    }

    public static function assertIsLike(mixed $expected, mixed $actual, string $messsage = ''): void
    {
        Assert::assertThat($actual, new IsLike($expected), $messsage);
    }

    public static function assertIsNotLike(mixed $expected, mixed $actual, string $messsage = ''): void
    {
        Assert::assertThat($actual, new LogicalNot(new IsLike($expected)), $messsage);
    }

    /**
     * @param iterable<mixed,mixed> $expected
     */
    final public static function iteratesLike(iterable $expected, bool $rewind = false): IteratesLike
    {
        return new IteratesLike($expected, $rewind);
    }

    /**
     * @param iterable<mixed, mixed> $expected
     */
    public static function assertIteratesLike(iterable $expected, mixed $actual, bool $rewind = false, string $messsage = ''): void
    {
        Assert::assertThat($actual, new IteratesLike($expected, rewind: $rewind), $messsage);
    }

    /**
     * @param iterable<mixed, mixed> $expected
     */
    public static function assertDoesNotIterateLike(iterable $expected, mixed $actual, bool $rewind = false, string $messsage = ''): void
    {
        Assert::assertThat($actual, new LogicalNot(new IteratesLike($expected, rewind: $rewind)), $messsage);
    }

    final public static function isUndefined(): IsUndefined
    {
        return new IsUndefined();
    }

    /**
     * @template E of Throwable
     * @param class-string<E>|E $expected
     * @return Throws<E>
     */
    final public static function throws(string|Throwable $expected = Throwable::class): Throws
    {
        return new Throws($expected);
    }

    /**
     * @param class-string<Throwable>|Throwable $expected
     * @param callable():mixed $actual
     */
    public static function assertThrows(string|Throwable $expected, callable $actual, string $messsage = ''): void
    {
        Assert::assertThat($actual, new Throws($expected), $messsage);
    }

    /**
     * @param class-string<Throwable>|Throwable $expected
     * @param callable():mixed $actual
     */
    public static function assertDoesNotThrow(string|Throwable $expected, callable $actual, string $messsage = ''): void
    {
        Assert::assertThat($actual, new LogicalNot(new Throws($expected)), $messsage);
    }
}
