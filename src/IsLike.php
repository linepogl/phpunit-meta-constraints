<?php

declare(strict_types=1);

namespace PHPUnitMetaConstraints;

use Override;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\Constraint\Operator;
use PHPUnit\Util\Exporter;
use PHPUnitMetaConstraints\Util\CustomAssert;
use PHPUnitMetaConstraints\Util\IsLikeErrorDetails;
use PHPUnitMetaConstraints\Util\Util;

class IsLike extends AbstractConstraint
{
    public function __construct(
        private readonly mixed $expected,
    ) {
    }

    #[Override]
    public function toString(): string
    {
        return $this->expected instanceof Constraint ? $this->expected->toString() : 'is like ' . Util::anyToString($this->expected);
    }

    #[Override]
    public function toStringInContext(Operator $operator, mixed $role): string
    {
        if ($this->expected instanceof Constraint) {
            return $this->expected->toStringInContext($operator, $role);
        }

        if ($operator instanceof LogicalNot) {
            return 'is not like ' . Util::anyToString($this->expected);
        }

        return '';
    }

    #[Override]
    protected function doEvaluate(mixed $actual, CustomAssert $assert, ?IsLikeErrorDetails $errorDetails = null): void
    {
        $errorDetails ??= new IsLikeErrorDetails($this->expected, $actual);
        if ($this->expected instanceof Constraint) {
            $assert->assertThat($actual, $this->expected, $errorDetails->prependMessage(), $errorDetails->comparisonFailure());
        } elseif (is_array($this->expected)) {
            if (array_is_list($this->expected)) {
                $this->doEvaluateIsLikeList($this->expected, $actual, $assert, $errorDetails);
            } else {
                $this->doEvaluateIsLikeArray($this->expected, $actual, $assert, $errorDetails);
            }
        } else {
            $assert->assertIs($this->expected, $actual, $errorDetails->prependMessage(), $errorDetails->comparisonFailure());
        }
    }

    /**
     * @param list<mixed> $expected
     */
    private function doEvaluateIsLikeList(array $expected, mixed $actual, CustomAssert $assert, IsLikeErrorDetails $errorDetails): void
    {
        $assert->assertIsIterable($actual, $errorDetails->prependMessage(), $errorDetails->comparisonFailure());
        $actualArray = [];
        foreach ($actual as $key => $value) {
            if (!is_int($key)) {
                $assert->fail(
                    $errorDetails->prependMessage('Expected keys of type int, got ' . get_debug_type($key) . ' instead'),
                    $errorDetails->comparisonFailure(),
                );
            }
            $assert->assertArrayNotHasKey(
                $key,
                $actualArray,
                $errorDetails->prependMessage('Expected unique keys, but ' . Exporter::export($key) . ' was duplicated'),
                $errorDetails->comparisonFailure(),
            );
            $actualArray[$key] = $value;
        }
        $realCount = 0;
        /** @var int<0,max> $index */
        foreach ($expected as $index => $value) {
            if ($value instanceof IsUndefined) {
                $assert->assertArrayNotHasKey(
                    $index,
                    $actualArray,
                    $errorDetails->prependMessage(),
                    $errorDetails->comparisonFailure(),
                );
            } else {
                $realCount++;
                $assert->assertArrayHasKey(
                    $index,
                    $actualArray,
                    $errorDetails->prependMessage(),
                    $errorDetails->comparisonFailure(),
                );

                new IsLike($value)->doEvaluate($actualArray[$index], $assert, $errorDetails->sub($index));
            }
        }
        $assert->assertCount(
            $realCount,
            $actualArray,
            $errorDetails->prependMessage(),
            $errorDetails->comparisonFailure(),
        );
    }

    /**
     * @param array<mixed> $expected
     */
    private function doEvaluateIsLikeArray(array $expected, mixed $actual, CustomAssert $assert, IsLikeErrorDetails $errorDetails): void
    {
        $assert->assertIsIterable($actual, $errorDetails->prependMessage(), $errorDetails->comparisonFailure());
        $actualArray = [];
        foreach ($actual as $key => $value) {
            if (!is_string($key) && !is_int($key)) {
                $assert->fail(
                    $errorDetails->prependMessage('Expected keys of type int|string, got ' . get_debug_type($key) . ' instead'),
                    $errorDetails->comparisonFailure(),
                );
            }
            $assert->assertArrayNotHasKey(
                $key,
                $actualArray,
                $errorDetails->prependMessage('Expected unique keys, but ' . Exporter::export($key) . ' was duplicated'),
                $errorDetails->comparisonFailure(),
            );
            $actualArray[$key] = $value;
        }
        foreach ($expected as $key => $value) {
            if ($value instanceof IsUndefined) {
                $assert->assertArrayNotHasKey(
                    $key,
                    $actualArray,
                    $errorDetails->prependMessage(),
                    $errorDetails->comparisonFailure(),
                );
            } else {
                $assert->assertArrayHasKey(
                    $key,
                    $actualArray,
                    $errorDetails->prependMessage(),
                    $errorDetails->comparisonFailure(),
                );

                new IsLike($value)->doEvaluate($actualArray[$key], $assert, $errorDetails->sub($key));
            }
        }
    }
}
