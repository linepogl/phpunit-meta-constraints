<?php

declare(strict_types=1);

namespace PHPUnitMetaConstraints\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnitMetaConstraints\IsUndefined;
use PHPUnitMetaConstraints\Util\PhpUnitMetaConstraintsTrait;
use RuntimeException;

class IsUndefinedTest extends TestCase
{
    use PhpUnitMetaConstraintsTrait;

    public function test_is_undefined(): void
    {
        $this->assertThrows(RuntimeException::class, static fn() => new IsUndefined()->evaluate(null));
    }

    public function test_to_string(): void
    {
        $this->assertIs('is undefined', new IsUndefined()->toString());
    }
}
