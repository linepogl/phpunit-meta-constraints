<?php

declare(strict_types=1);

namespace PHPUnitMetaConstraints\Util;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Util\Exporter;
use SebastianBergmann\Comparator\ComparisonFailure;

final class Util
{
    public static function expectationFailure(
        string $message,
        mixed $expected,
        mixed $actual,
        ?string $expectedAsString = null,
        ?string $actualAsString = null,
        ?string $comparisonFailureMessage = null,
    ): ExpectationFailedException {
        return new ExpectationFailedException(
            $message,
            self::comparisonFailure($expected, $actual, $expectedAsString, $actualAsString, $comparisonFailureMessage ?? $message),
        );
    }

    public static function comparisonFailure(
        mixed $expected,
        mixed $actual,
        ?string $expectedAsString = null,
        ?string $actualAsString = null,
        ?string $message = null,
    ): ComparisonFailure {
        return new ComparisonFailure(
            $expected,
            $actual,
            $expectedAsString ?? Exporter::export($expected),
            $actualAsString ?? Exporter::export($actual),
            $message ?? '',
        );
    }

    public static function anyToString(mixed $any): string
    {
        return match (true) {
            null === $any,
            is_scalar($any) => Exporter::export($any),
            is_array($any) => 'an array',
            $any instanceof Constraint => $any->toString(),
            default => 'some ' . get_debug_type($any),
        };
    }
}
