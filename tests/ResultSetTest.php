<?php

declare(strict_types=1);

namespace Koriym\ResultSet;

use PHPUnit\Framework\TestCase;

use function count;

class ResultSetTest extends TestCase
{
    /** @var array<array<scalar>> */
    protected $resultSet;

    protected function setUp(): void
    {
        $this->resultSet = [
            ['id' => 1, 'name' => 'ray'],
            ['id' => 2, 'name' => 'di'],
            ['id' => 3, 'name' => 'aop'],
        ];
    }

    public function testRowList(): void
    {
        /** @var EntityList<FakeUser> $rowList */
        $rowList = ResultSet::entityList($this->resultSet, FakeUser::class);
        $this->assertContainsOnlyInstancesOf(FakeUser::class, $rowList);
        $this->assertSame(3, count($rowList));
    }

    public function testRowFactory(): void
    {
        $rowList = ResultSet::rowListFactory(
            $this->resultSet,
            static function (...$elements): FakeUser {
                return new FakeUser(...$elements);
            }
        );
        $this->assertContainsOnlyInstancesOf(FakeUser::class, $rowList);
        $this->assertSame(3, count($rowList));
    }
}
