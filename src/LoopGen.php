<?php

declare(strict_types=1);

namespace Koriym\Loop;

use ArrayIterator;
use Generator;

use function array_values;
use function is_array;

use const PHP_MAJOR_VERSION;

/**
 * Generate loops for a given set of elements
 *
 * @template T of object
 * @implements LoopGenInterface<T>
 */
final class LoopGen implements LoopGenInterface
{
    /**
     * {@inheritDoc}
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
     * @SuppressWarnings("UnusedPrivateMethod")
     */
    private function newEntity(string $entity, array $elements)
    {
        if (PHP_MAJOR_VERSION < 8) {
            $elements = array_values($elements);
        }

        /** @psalm-suppress MixedMethodCall */
        return new $entity(...$elements);
    }
}
