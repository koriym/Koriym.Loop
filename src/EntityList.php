<?php

declare(strict_types=1);

namespace Koriym\ResultSet;

use Generator;
use LogicException;

use function assert;
use function class_exists;
use function count;
use function is_countable;
use function is_int;
use function is_string;

/**
 * @template T of object
 */
final class EntityList implements EntityListInterface
{
    /** @var iterable<array<scalar>> */
    private $elements;

    /** @var class-string<T> */
    private $entity;

    /**
     * @param iterable<array<scalar>> $elements
     * @param class-string<T>         $entity
     */
    public function __construct(
        iterable $elements,
        string $entity
    ) {
        $this->elements = $elements;
        assert(class_exists($entity));
        $this->entity = $entity;
    }

    /** @return Generator<array-key, T, string, void> */
    public function getIterator(): Generator
    {
        assert(class_exists($this->entity));
        foreach ($this->elements as $key => $element) {
            assert(is_int($key) || is_string($key));
            /** @psalm-suppress MixedMethodCall */
            $entity = new $this->entity(...$element);

            yield $key => $entity;
        }
    }

    public function count(): int
    {
        if (is_countable($this->elements)) {
            return count($this->elements);
        }

        throw new LogicException();
    }
}
