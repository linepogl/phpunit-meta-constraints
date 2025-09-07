<?php

declare(strict_types=1);

namespace PHPUnitMetaConstraints\Util;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\ArrayHasKey;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\Count;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\NativeType;
use PHPUnit\Util\Exporter;
use PHPUnitMetaConstraints\Is;
use PHPUnitMetaConstraints\IteratesLike;
use SebastianBergmann\Comparator\ComparisonFailure;

final class CustomAssert
{
    public function __construct(
        public readonly string $customMessage = '',
    ) {
    }

    public function expectationFailure(string $message, mixed $expected, mixed $actual, ?string $expectedAsString = null, ?string $actualAsString = null): ExpectationFailedException
    {
        return Util::expectationFailure(
            message: '' === $this->customMessage ? $message : $this->customMessage,
            expected: $expected,
            actual: $actual,
            expectedAsString: $expectedAsString ?? Exporter::export($expected),
            actualAsString: $actualAsString ?? Exporter::export($actual),
            comparisonFailureMessage: $message,
        );
    }

    /**
     * @param string|(callable(string):string) $message
     */
    public function assertThat(mixed $actual, Constraint $constraint, string|callable $message = '', ?ComparisonFailure $comparisonFailure = null): void
    {
        try {
            Assert::assertThat($actual, $constraint);
        } catch (ExpectationFailedException $ex) {
            $this->fail($message, $comparisonFailure, $ex);
        }
    }

    /**
     * @param string|(callable(string):string) $message
     */
    public function fail(string|callable $message = '', ?ComparisonFailure $comparisonFailure = null, ?ExpectationFailedException $previous = null): never
    {
        $comparisonMessage = $previous?->getMessage() ?? '';
        if (is_string($message) && '' !== $message) {
            $comparisonMessage = $message;
        }

        $msg = $this->customMessage;
        if ('' === $msg) {
            $msg = $comparisonMessage;
        }

        if (is_callable($message)) {
            /** @var string $msg */
            $msg = $message($msg);
        }
        if (null !== $comparisonFailure) {
            if ('' === $comparisonFailure->getMessage()) {
                $comparisonFailure = new ComparisonFailure(
                    $comparisonFailure->getExpected(),
                    $comparisonFailure->getActual(),
                    $comparisonFailure->getExpectedAsString(),
                    $comparisonFailure->getActualAsString(),
                    $comparisonMessage,
                );
            }
        }

        throw new ExpectationFailedException($msg, $comparisonFailure ?? $previous?->getComparisonFailure(), $previous);
    }

    /**
     * @param string|callable(string):string $message
     */
    public function assertIs(mixed $expected, mixed $actual, string|callable $message = '', ?ComparisonFailure $comparisonFailure = null): void
    {
        $this->assertThat($actual, new Is($expected), $message, $comparisonFailure);
    }

    /**
     * @param class-string $expected
     * @param string|callable(string):string $message
     */
    public function assertInstanceOf(string $expected, mixed $actual, string|callable $message = '', ?ComparisonFailure $comparisonFailure = null): void
    {
        $this->assertThat($actual, new IsInstanceOf($expected), $message, $comparisonFailure);
    }

    /**
     * @param string|callable(string):string $message
     */
    public function assertEquals(mixed $expected, mixed $actual, string|callable $message = '', ?ComparisonFailure $comparisonFailure = null): void
    {
        $this->assertThat($actual, new IsEqual($expected), $message, $comparisonFailure);
    }

    /**
     * @param string|callable(string):string $message
     */
    public function assertSame(mixed $expected, mixed $actual, string|callable $message = '', ?ComparisonFailure $comparisonFailure = null): void
    {
        $this->assertThat($actual, new IsIdentical($expected), $message, $comparisonFailure);
    }

    /**
     * @template K
     * @template V
     * @param iterable<K, V> $expected
     * @param string|callable(string):string $message
     * @phpstan-assert iterable<K, V> $actual
     */
    public function assertIteratesLike(iterable $expected, mixed $actual, string|callable $message = '', ?ComparisonFailure $comparisonFailure = null): void
    {
        $this->assertThat($actual, new IteratesLike($expected), $message, $comparisonFailure);
    }

    /**
     * @param string|callable(string):string $message
     */
    public function assertCount(int $expected, mixed $actual, string|callable $message = '', ?ComparisonFailure $comparisonFailure = null): void
    {
        $this->assertThat($actual, new Count($expected), $message, $comparisonFailure);
    }

    /**
     * @param string|callable(string):string $message
     * @phpstan-assert iterable<mixed, mixed> $actual
     */
    public function assertIsIterable(mixed $actual, string|callable $message = '', ?ComparisonFailure $comparisonFailure = null): void
    {
        $this->assertThat($actual, new IsType(NativeType::Iterable), $message, $comparisonFailure);
    }

    /**
     * @param string|callable(string):string $message
     * @phpstan-assert callable $actual
     */
    public function assertIsCallable(mixed $actual, string|callable $message = '', ?ComparisonFailure $comparisonFailure = null): void
    {
        $this->assertThat($actual, new IsType(NativeType::Callable), $message, $comparisonFailure);
    }

    /**
     * @param array<mixed, mixed> $array
     * @param string|callable(string):string $message
     */
    public function assertArrayHasKey(mixed $key, array $array, string|callable $message = '', ?ComparisonFailure $comparisonFailure = null): void
    {
        $this->assertThat($array, new ArrayHasKey($key), $message, $comparisonFailure);
    }

    /**
     * @param array<mixed, mixed> $array
     * @param string|callable(string):string $message
     */
    public function assertArrayNotHasKey(mixed $key, array $array, string|callable $message = '', ?ComparisonFailure $comparisonFailure = null): void
    {
        $this->assertThat($array, new LogicalNot(new ArrayHasKey($key)), $message, $comparisonFailure);
    }
}
