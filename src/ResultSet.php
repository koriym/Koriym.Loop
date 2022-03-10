<?php

declare(strict_types=1);

namespace Koriym\ResultSet;

final class ResultSet
{
    /**
     * @param array<array<scalar>> $resultSetList
     * @param class-string<T>      $entityClass
     *
     * @return EntityList<T>
     *
     * @template T of object
     */
    public static function entityList(array $resultSetList, string $entityClass): iterable
    {
        return new EntityList($resultSetList, $entityClass);
    }

    /**
     * @param array<array<scalar>> $resultSetList
     * @param callable(scalar      ...$scalar):T  $factory
     *
     * @return EntityListFactory<T>
     *
     * @template T of object
     */
    public static function rowListFactory(array $resultSetList, callable $factory): iterable
    {
        return new EntityListFactory($resultSetList, $factory);
    }
}
