<?php

declare(strict_types=1);

namespace PHPUnitMetaConstraints;

use Override;
use PHPUnitMetaConstraints\Util\CustomAssert;

final class IsUndefined extends AbstractConstraint
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
    protected function doEvaluate(mixed $actual, CustomAssert $assert): void
    {
        $assert->fail('This should never be called directly, use IsLike instead.');
    }
}
