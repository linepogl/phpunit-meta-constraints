<?php

declare(strict_types=1);

namespace PHPUnitMetaConstraints\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use PHPUnitMetaConstraints\Throws;
use PHPUnitMetaConstraints\Util\PhpUnitMetaConstraintsTrait;
use RuntimeException;

class ThrowsTest extends TestCase
{
    use PhpUnitMetaConstraintsTrait;

    public function test_throws(): void
    {
        $f1 = static fn() => throw new RuntimeException('Test');
        $f2 = static fn() => null;
        static::assertTrue(new Throws(RuntimeException::class)->evaluate($f1, '', true));
        static::assertTrue(new Throws(new RuntimeException('Test'))->evaluate($f1, '', true));
        static::assertFalse(new Throws(new RuntimeException('Test1'))->evaluate($f1, '', true));
        static::assertFalse(new Throws(InvalidArgumentException::class)->evaluate($f1, '', true));
        static::assertFalse(new Throws(new InvalidArgumentException('Test'))->evaluate($f1, '', true));
        static::assertFalse(new Throws(RuntimeException::class)->evaluate($f2, '', true));
        static::assertFalse(new Throws(new RuntimeException('Test'))->evaluate($f2, '', true));
    }

    public function test_to_string(): void
    {
        $this->assertIs('throws RuntimeException', new Throws(RuntimeException::class)->toString());
        $this->assertIs('throws RuntimeException', new Throws(new RuntimeException())->toString());
    }
}
