<?php

declare(strict_types=1);

namespace PHPUnitMetaConstraints;

use Override;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\Constraint\Operator;
use PHPUnitMetaConstraints\Util\CustomAssert;

class IsUndefined extends AbstractConstraint
{
    public function __construct(
    ) {
    }

    #[Override]
    public function toString(): string
    {
        return 'is undefined';
    }

    #[Override]
    public function toStringInContext(Operator $operator, mixed $role): string
    {
        if ($operator instanceof LogicalNot) {
            return 'is not undefined';
        }
        return '';
    }

    #[Override]
    protected function doEvaluate(mixed $actual, CustomAssert $assert): void
    {
        $assert->fail('This value shouldn\'t be defined.');
    }
}
