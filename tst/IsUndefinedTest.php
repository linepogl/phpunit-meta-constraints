<?php

declare(strict_types=1);

namespace PHPUnitMetaConstraints\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnitMetaConstraints\Util\PhpUnitMetaConstraintsTrait;
use RuntimeException;

class IsUndefinedTest extends TestCase
{
    use PhpUnitMetaConstraintsTrait;

    public function test_is_undefined(): void
    {
        self::assertThrows(RuntimeException::class, static fn() => self::isUndefined()->evaluate(null));
    }

    public function test_to_string(): void
    {
        self::assertIs('is undefined', self::isUndefined()->toString());
    }
}
