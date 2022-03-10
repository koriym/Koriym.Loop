<?php

declare(strict_types=1);

namespace Koriym\ResultSet;

use Generator;

use function current;
use function next;

/**
 * @template T of object
 */
final class ListGen
{
    // phpcs:disable
    /**
     * @param array<array<string, mixed>>  $elements
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
        array $elements,
        string $entity,
        array $extraParams = [],
        ?callable $factory = null
    ): Generator {
        if ($elements === []) {
            return;
        }

        $factory = $factory ?? [$this, 'newEntity'];
        $iteration = 0;
        $current = current($elements) + $extraParams;
        $next = next($elements);
        $loop = $next ? new Loop(true, false, $iteration) : new Loop(true, true, $iteration);

        // first loop
        yield $loop => $factory($entity, ...$current);

        if (! $next) {
            return;
        }

        while (true) {
            $iteration++;
            $current = $next + $extraParams;
            $next = next($elements);
            if ($next === false) {
                yield new Loop(false, true, $iteration) => $factory($entity, ...$current);

                return;
            }

            yield new Loop(false, false, $iteration) => $factory($entity, ...$current);
        }
    }

    /**
     * @param class-string<T> $entity
     * @param mixed           ...$elements
     *
     * @return T
     *
     * @psalm-suppress MixedMethodCall
     */
    private function newEntity(string $entity, ...$elements)
    {
        return new $entity(...$elements);
    }
}
