<?php

declare(strict_types=1);

namespace PHPUnitMetaConstraints;

use Override;
use PHPUnitMetaConstraints\Util\CustomAssert;
use ReflectionClass;
use Throwable;

/**
 * @template E of Throwable
 */
class Throws extends AbstractConstraint
{
    /**
     * @param class-string<E>|Throwable $expected
     */
    public function __construct(
        private readonly string|Throwable $expected = Throwable::class,
    ) {
    }

    #[Override]
    public function toString(): string
    {
        return 'throws ' . (is_string($this->expected) ? $this->expected : $this->expected::class);
    }

    /**
     * @return array<string, mixed>
     */
    private function normalizeException(Throwable $exception): array
    {
        $r = [];
        foreach (new ReflectionClass($exception)->getProperties() as $property) {
            if ($property->isStatic() || in_array($property->getName(), ['file', 'line', 'trace', 'serializableTrace'])) {
                continue;
            }
            $value = $property->getValue($exception);
            if ($value instanceof Throwable) {
                $value = $this->normalizeException($value);
            }
            $r[$property->getName()] = $value;
        }
        ksort($r);
        return $r;
    }

    #[Override]
    protected function doEvaluate(mixed $actual, CustomAssert $assert): void
    {
        $assert->assertIsCallable($actual);

        $class = is_string($this->expected) ? $this->expected : $this->expected::class;
        try {
            $actual();
        } catch (Throwable $ex) {
            $assert->assertInstanceOf($class, $ex);
            if ($this->expected instanceof Throwable) {
                $exp = $this->normalizeException($this->expected);
                $act = $this->normalizeException($ex);
                $assert->assertIs($exp, $act, 'Failed asserting that the two exceptions are equal.');
            }
            return;
        }
        $assert->fail("Expected $class to be thrown, but nothing was.");
    }
}
