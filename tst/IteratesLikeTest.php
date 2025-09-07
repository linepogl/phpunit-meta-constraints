<?php

declare(strict_types=1);

namespace PHPUnitMetaConstraints\Tests;

use ArrayIterator;
use Exception;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use PHPUnitMetaConstraints\IteratesLike;
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
        $constraint = new IteratesLike($expected);
        if (null === $error) {
            static::assertTrue($constraint->evaluate($actual, '', true));
            $this->assertDoesNotThrow(Throwable::class, static fn() => $constraint->evaluate($actual));
        } else {
            static::assertFalse($constraint->evaluate($actual, '', true));
            $this->assertThrows(
                Util::expectationFailure($error, $expected, $actual, $expectedAsString, $actualAsString),
                static fn() => $constraint->evaluate($actual),
            );
            $this->assertThrows(
                Util::expectationFailure('Custom message', $expected, $actual, $expectedAsString, $actualAsString, $error),
                static fn() => $constraint->evaluate($actual, 'Custom message'),
            );
        }
    }

    public function test_iterates_like_rewind(): void
    {
        $this->assertDoesNotThrow(Throwable::class, static fn() => new IteratesLike([1, 2], rewind: true)->evaluate([1, 2]));
        $this->assertDoesNotThrow(Throwable::class, fn() => $this->assertIteratesLike([1, 2], new ArrayIterator([1, 2]), rewind: true));
        $this->assertThrows(
            new Exception('Cannot traverse an already closed generator'),
            fn() => $this->assertIteratesLike([1, 2], (static function () {
                yield 1;
                yield 2;
            })(), rewind: true),
        );
    }

    public function test_to_string(): void
    {
        $this->assertIs('iterates like an array', new IteratesLike([1, 2])->toString());
        $this->assertIs('iterates like an array and rewinds', new IteratesLike([1, 2], rewind: true)->toString());
    }
}
