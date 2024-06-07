<?php

declare(strict_types=1);

namespace Koriym\Loop;

use ArrayIterator;
use Generator;
use Iterator;

use function array_values;
use function is_array;

use const PHP_MAJOR_VERSION;

/**
 * @template T of object
 */
final class LoopGen
{
    // phpcs:disable
    /**
     * @param Iterator<array-key, array<string, mixed>>|array<array<string, mixed>>  $elements
     * @param class-string<T>              $entity
     * @param array<string, mixed>         $extraParams
     * @param callable(class-string, mixed...):T $factory
     *
     * @return Generator<Loop, T, mixed, void>
     *
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    public function __invoke(
        // phpcs:enable
        $elements,
        string $entity,
        array $extraParams = [],
        ?callable $factory = null
    ): Generator {
        if ($elements === []) {
            return;
        }

        $elements = is_array($elements) ? new ArrayIterator($elements) : $elements;
                 $factory = $factory ?? [$this, 'newEntity'];
                 $index = 0;
        while ($elements->valid()) {
            $current = $elements->current() + $extraParams;
            $isFirst = $index === 0;
            $elements->next();
            $isLast = ! $elements->valid();

            yield new Loop($isFirst, $isLast, $index) => $factory($entity, $current);

            $index++;
        }
    }

    /**
     * @param class-string<T>      $entity
     * @param array<string, mixed> $elements
     *
     * @return T
     *
     * @psalm-suppress MixedMethodCall
     * @SuppressWarnings("UnusedPrivateMethod")
     */
    private function newEntity(string $entity, array $elements)
    {
        if (PHP_MAJOR_VERSION < 8) {
            $elements = array_values($elements);
        }

        return new $entity(...$elements);
    }
}
