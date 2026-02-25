<?php

declare(strict_types=1);

namespace PHPUnitMetaConstraints\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnitMetaConstraints\Util\PHPUnitMetaConstraintsTrait;
use RuntimeException;

class IsUndefinedTest extends TestCase
{
    use PHPUnitMetaConstraintsTrait;

    public function test_is_undefined(): void
    {
        self::assertThrows(RuntimeException::class, static fn() => self::isUndefined()->evaluate(null));
        self::assertDoesNotThrow(RuntimeException::class, static fn() => self::isDefined()->evaluate(null));
    }

    public function test_to_string(): void
    {
        self::assertIs('is undefined', self::isUndefined()->toString());
        self::assertIs('is defined', self::isDefined()->toString());
        self::assertIs('is defined', static::logicalNot(self::isUndefined())->toString());
        self::assertIs('is undefined', static::logicalNot(self::isDefined())->toString());
        self::assertIs('is undefined and is undefined', static::logicalAnd(self::isUndefined(), self::isUndefined())->toString());
        self::assertIs('is defined and is defined', static::logicalAnd(self::isDefined(), self::isDefined())->toString());
    }
}
