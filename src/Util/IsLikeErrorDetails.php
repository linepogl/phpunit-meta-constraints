<?php

declare(strict_types=1);

namespace PHPUnitMetaConstraints\Util;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Util\Exporter;
use SebastianBergmann\Comparator\ComparisonFailure;

class IsLikeErrorDetails
{
    public function __construct(
        public readonly mixed $expected,
        public readonly mixed $actual,
        public readonly string $path = '',
    ) {
    }

    public function sub(mixed $path): self
    {
        return new self(
            $this->expected,
            $this->actual,
            $this->path . '→' . Exporter::export($path),
        );
    }

    /**
     * @return callable(string):string
     */
    public function prependMessage(string $specialMessage = ''): callable
    {
        return fn(string $message)
            => (
                '' === $this->path
                ? ($this->expected instanceof Constraint ? '' : 'Failed asserting that ' . Util::anyToString($this->actual) . ' is like ' . Util::anyToString($this->expected) . ".\n")
                : 'Failed asserting that ' . Util::anyToString($this->actual) . ' is like ' . Util::anyToString($this->expected) . ".\n$this->path: "
            )
            . ('' === $specialMessage ? '' : "$specialMessage\n")
            . $message;
    }

    public function comparisonFailure(): ComparisonFailure
    {
        return Util::comparisonFailure($this->expected, $this->actual);
    }
}
