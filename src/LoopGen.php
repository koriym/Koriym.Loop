<?php

declare(strict_types=1);

namespace Koriym\Loop;

use Generator;

use function array_values;
use function current;
use function next;

use const PHP_MAJOR_VERSION;

/**
 * @template T of object
 */
final class LoopGen
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
        $index = 0;
        $current = current($elements) + $extraParams;
        $next = next($elements);
        $loop = $next ? new Loop(true, false, $index) : new Loop(true, true, $index);

        // first loop
        yield $loop => $factory($entity, $current);

        if (! $next) {
            return;
        }

        while (true) {
            $index++;
            $current = $next + $extraParams;
            $next = next($elements);
            if ($next === false) {
                yield new Loop(false, true, $index) => $factory($entity, $current);

                return;
            }

            yield new Loop(false, false, $index) => $factory($entity, $current);
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
