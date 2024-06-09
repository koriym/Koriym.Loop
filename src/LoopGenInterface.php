<?php

declare(strict_types=1);

namespace Koriym\Loop;

use Generator;
use Iterator;

/**
 * @template T of object
 */
interface LoopGenInterface
{
    /**
     * This function generates a loop for the given elements. It takes an iterator or an array of elements,
     * a class-string entity, an optional array of extra parameters, and an optional factory callable.
     * It returns a generator that yields a new Loop object for each element.
     *
     * @param Iterator<array-key, array<string, mixed>>|array<array<string, mixed>> $elements
     * @param class-string<T>                                                       $entity
     * @param array<string, mixed>                                                  $extraParams
     * @param callable(class-string, mixed...):T                                    $factory
     *
     * @return Generator<Loop, T, mixed, void>
     */
    public function __invoke($elements, string $entity, array $extraParams = [], ?callable $factory = null): Generator;
}
