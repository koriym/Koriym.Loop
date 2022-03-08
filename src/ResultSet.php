<?php

declare(strict_types=1);

namespace Koriym\ResultSet;

final class ResultSet
{
    /**
     * @param array<array<scalar>> $resultSetList
     * @param class-string<T>      $entityClass
     *
     * @return RowList<T>
     * @template T of object
     */
    public static function rowList(array $resultSetList, string $entityClass): iterable
    {
        return new RowList($resultSetList, $entityClass);
    }

    /**
     * @param array<array<scalar>> $resultSetList
     * @param callable(scalar      ...$scalar):T  $factory
     *
     * @return RowListFactory<T>
     * @template T of object
     */
    public static function rowListFactory(array $resultSetList, callable $factory): iterable
    {
        return new RowListFactory($resultSetList, $factory);
    }
}
