<?php

declare(strict_types=1);

namespace Koriym\ResultSet;

use _PHPStan_156cb69be\Symfony\Component\Console\Exception\LogicException;
use Generator;

use function assert;
use function count;
use function is_countable;
use function is_int;
use function is_string;

/**
 * @template T of object
 */
final class EntityListFactory implements EntityListInterface
{
    /** @var iterable<array<scalar>> */
    private $elements;

    /** @var callable */
    private $factory;

    /**
     * @param iterable<array<scalar>> $elements
     * @param callable(scalar         ...$scalar):T $factory
     */
    public function __construct(
        iterable $elements,
        callable $factory
    ) {
        $this->elements = $elements;
        $this->factory = $factory;
    }

    /** @return Generator<array-key, T, string, void> */
    public function getIterator(): Generator
    {
        foreach ($this->elements as $key => $element) {
            assert(is_int($key) || is_string($key));
            /** @var T|null $entity */
            $entity = ($this->factory)(...$element);
            if ($entity === null) {
                continue;
            }

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
