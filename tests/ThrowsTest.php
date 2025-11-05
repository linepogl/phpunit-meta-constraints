<?php

declare(strict_types=1);

namespace PHPUnitMetaConstraints\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use PHPUnitMetaConstraints\Util\PHPUnitMetaConstraintsTrait;
use RuntimeException;

class ThrowsTest extends TestCase
{
    use PHPUnitMetaConstraintsTrait;

    public function test_throws(): void
    {
        $f1 = static fn() => throw new RuntimeException('Test');
        $f2 = static fn() => null;
        static::assertTrue(self::throws(RuntimeException::class)->evaluate($f1, '', true));
        static::assertTrue(self::throws(new RuntimeException('Test'))->evaluate($f1, '', true));
        static::assertFalse(self::throws(new RuntimeException('Test1'))->evaluate($f1, '', true));
        static::assertFalse(self::throws(InvalidArgumentException::class)->evaluate($f1, '', true));
        static::assertFalse(self::throws(new InvalidArgumentException('Test'))->evaluate($f1, '', true));
        static::assertFalse(self::throws(RuntimeException::class)->evaluate($f2, '', true));
        static::assertFalse(self::throws(new RuntimeException('Test'))->evaluate($f2, '', true));
    }

    public function test_to_string(): void
    {
        self::assertIs('throws RuntimeException', self::throws(RuntimeException::class)->toString());
        self::assertIs('throws RuntimeException', self::throws(new RuntimeException())->toString());
        self::assertIs('does not throw RuntimeException', static::logicalNot(self::throws(RuntimeException::class))->toString());
        self::assertIs('does not throw RuntimeException', static::logicalNot(self::throws(new RuntimeException()))->toString());

        self::assertIs('throws RuntimeException and throws RuntimeException', static::logicalAnd(self::throws(RuntimeException::class), self::throws(RuntimeException::class))->toString());
    }
}
