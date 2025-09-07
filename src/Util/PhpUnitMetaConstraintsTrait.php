<?php

declare(strict_types=1);

namespace PHPUnitMetaConstraints\Util;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnitMetaConstraints\Is;
use PHPUnitMetaConstraints\IsLike;
use PHPUnitMetaConstraints\IteratesLike;
use PHPUnitMetaConstraints\Throws;
use Throwable;

/**
 * @phpstan-require-extends Assert
 */
trait PhpUnitMetaConstraintsTrait
{
    public static function assertIs(mixed $expected, mixed $actual, string $messsage = ''): void
    {
        Assert::assertThat($actual, new Is($expected), $messsage);
    }

    public static function assertIsNot(mixed $expected, mixed $actual, string $messsage = ''): void
    {
        Assert::assertThat($actual, new LogicalNot(new Is($expected)), $messsage);
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
     * @param iterable<mixed, mixed> $expected
     * @param iterable<mixed, mixed> $actual
     */
    public static function assertIteratesLike(iterable $expected, iterable $actual, bool $rewind = false, string $messsage = ''): void
    {
        Assert::assertThat($actual, new IteratesLike($expected, rewind: $rewind), $messsage);
    }

    /**
     * @param iterable<mixed, mixed> $expected
     * @param iterable<mixed, mixed> $actual
     */
    public static function assertDoesNotIterateLike(iterable $expected, iterable $actual, bool $rewind = false, string $messsage = ''): void
    {
        Assert::assertThat($actual, new LogicalNot(new IteratesLike($expected, rewind: $rewind)), $messsage);
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
