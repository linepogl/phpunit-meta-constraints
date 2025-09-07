<?php

declare(strict_types=1);

namespace PHPUnitMetaConstraints\Tests;

use PHPUnit\Framework\Constraint\IsAnything;
use PHPUnit\Framework\TestCase;
use PHPUnitMetaConstraints\Util\PhpUnitMetaConstraintsTrait;
use Throwable;

class IsAnythingTest extends TestCase
{
    use PhpUnitMetaConstraintsTrait;

    public function test_is_anything(): void
    {
        $this->assertDoesNotThrow(Throwable::class, static fn() => new IsAnything()->evaluate(null));
    }

    public function test_to_string(): void
    {
        $this->assertIs('is anything', new IsAnything()->toString());
    }
}
