<?php

declare(strict_types=1);

namespace PHPUnitMetaConstraints\Tests;

use ArrayIterator;
use Exception;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use PHPUnitMetaConstraints\Util\PhpUnitMetaConstraintsTrait;
use PHPUnitMetaConstraints\Util\Util;
use Throwable;

class IteratesLikeTest extends TestCase
{
    use PhpUnitMetaConstraintsTrait;

    /**
     * @return iterable<string, array{iterable<mixed, mixed>, mixed}|array{iterable<mixed, mixed>, mixed, ?string}>
     */
    public static function cases(): iterable
    {
        yield 'array 1' => [[], []];
        yield 'array 2' => [[], [1], 'Failed asserting that two iterables iterate the same way.'];

        yield 'array 3' => [['a' => 1, 'b' => 2], ['a' => 1, 'b' => 2]];
        yield 'array 4' => [['a' => 1, 'b' => 2], ['b' => 2, 'a' => 1], 'Failed asserting that two iterables iterate the same way.'];
        yield 'array 5' => [['a' => 1, 'b' => 2], ['a' => 1, 'b' => '2'], 'Failed asserting that two iterables iterate the same way.'];
        yield 'array 6' => [['a' => 1, 'b' => 2], ['a' => 1, 'b' => 2, 'c' => 3], 'Failed asserting that two iterables iterate the same way.'];
        yield 'array 7' => [['a' => 1, 'b' => 2], ['a' => 1], 'Failed asserting that two iterables iterate the same way.'];

        yield 'not iterable' => [['a' => 1, 'b' => 2], 1, 'Failed asserting that 1 is of type iterable.'];
    }

    /**
     * @param iterable<mixed, mixed> $expected
     */
    #[DataProvider('cases')]
    public function test_iterates_like(iterable $expected, mixed $actual, ?string $error = null, ?string $expectedAsString = null, ?string $actualAsString = null): void
    {
        $constraint = self::iteratesLike($expected);
        if (null === $error) {
            static::assertTrue($constraint->evaluate($actual, '', true));
            self::assertDoesNotThrow(Throwable::class, static fn() => $constraint->evaluate($actual));
            self::assertDoesNotThrow(Throwable::class, fn() => $this->assertIteratesLike($expected, $actual));
            self::assertThrows(Throwable::class, fn() => $this->assertDoesNotIterateLike($expected, $actual));
        } else {
            static::assertFalse($constraint->evaluate($actual, '', true));
            self::assertThrows(
                Util::expectationFailure($error, $expected, $actual, $expectedAsString, $actualAsString),
                static fn() => $constraint->evaluate($actual),
            );
            self::assertThrows(
                Util::expectationFailure('Custom message', $expected, $actual, $expectedAsString, $actualAsString, $error),
                static fn() => $constraint->evaluate($actual, 'Custom message'),
            );
            self::assertThrows(
                Util::expectationFailure('Custom message', $expected, $actual, $expectedAsString, $actualAsString, $error),
                fn() => $this->assertIteratesLike($expected, $actual, false, 'Custom message'),
            );
            self::assertDoesNotThrow(
                Throwable::class,
                fn() => $this->assertDoesNotIterateLike($expected, $actual, false),
            );
        }
    }

    public function test_iterates_like_rewind(): void
    {
        self::assertDoesNotThrow(Throwable::class, static fn() => self::iteratesLike([1, 2], rewind: true)->evaluate([1, 2]));
        self::assertDoesNotThrow(Throwable::class, fn() => $this->assertIteratesLike([1, 2], new ArrayIterator([1, 2]), rewind: true));
        self::assertThrows(
            new Exception('Cannot traverse an already closed generator'),
            fn() => $this->assertIteratesLike([1, 2], (static function () {
                yield 1;
                yield 2;
            })(), rewind: true),
        );
    }

    public function test_to_string(): void
    {
        $this->assertIs('iterates like an array', self::iteratesLike([1, 2])->toString());
        $this->assertIs('iterates like an array and rewinds', self::iteratesLike([1, 2], rewind: true)->toString());
    }
}
