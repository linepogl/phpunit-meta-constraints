<?php

declare(strict_types=1);

namespace PHPUnitMetaConstraints;

use Override;
use PHPUnitMetaConstraints\Util\CustomAssert;

final class IsDefined extends AbstractConstraint
{
    public function __construct(
    ) {
    }

    #[Override]
    public function toString(): string
    {
        return 'is defined';
    }

    #[Override]
    protected function doEvaluate(mixed $actual, CustomAssert $assert): void
    {
    }
}
