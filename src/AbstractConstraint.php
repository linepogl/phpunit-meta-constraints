<?php

declare(strict_types=1);

namespace PHPUnitMetaConstraints;

use Override;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnitMetaConstraints\Util\CustomAssert;

abstract class AbstractConstraint extends Constraint
{
    abstract protected function doEvaluate(mixed $actual, CustomAssert $assert): void;

    #[Override]
    final public function evaluate(mixed $other, string $description = '', bool $returnResult = false): ?bool
    {
        try {
            $this->doEvaluate($other, new CustomAssert($description));
        } catch (ExpectationFailedException $ex) {
            if ($returnResult) {
                return false;
            }
            if ('' !== $description) {
                $ex = new ExpectationFailedException($description, $ex->getComparisonFailure(), $ex);
            }
            throw $ex;
        }
        return $returnResult ?: null;
    }
}
