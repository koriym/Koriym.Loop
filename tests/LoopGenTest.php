<?php

declare(strict_types=1);

namespace Koriym\Loop;

use ArrayIterator;
use DateTime;
use PHPUnit\Framework\TestCase;

use function assert;

class LoopGenTest extends TestCase
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

    /**
     * @return array<array<string>>|ArrayIterator<array<string>>
     */
    public function dataProvider(): array
    {
        return [
            $this->resultSet,
            new ArrayIterator($this->resultSet),
        ];
    }

    /**
     * @param  array<array<string>>|ArrayIterator<array<string>> $resultSet
     *
     * @dataProvider dataProvider
     */
    public function testInvoke(iterable $resultSet): void
    {
        /** @var LoopGen<FakeUser> $list */
        $indexList = [];
        $iterationList = [];
        $list = (new LoopGen())($resultSet, FakeUser::class);
        foreach ($list as $loop => $user) {
            /** @var Loop $loop */
            $indexList[] = $loop->index;
            $iterationList[] = $loop->iteration;
            $this->assertInstanceOf(Loop::class, $loop);
            $this->assertInstanceOf(FakeUser::class, $user); // Not by assertContainsOnlyInstancesOf because for the debugging
        }

        $this->assertSame([0, 1, 2], $indexList);
        $this->assertSame([1, 2, 3], $iterationList);
        $this->assertFalse($loop->isFirst);
        $this->assertTrue($loop->isLast);
    }

    public function testEmptyList()
    {
        $list =  (new LoopGen())([], FakeUser::class);
        $item = null;
        foreach ($list as $item) {
            assert($item instanceof FakeUser);
        }

        $this->assertNull($item);
    }

    public function testSingleItem()
    {
        $list =  (new LoopGen())([['id' => 1, 'name' => 'ray']], FakeUser::class);
        foreach ($list as $loop => $item) {
            $this->assertInstanceOf(Loop::class, $loop);
        }

        $this->assertTrue($loop->isFirst);
        $this->assertTrue($loop->isLast);
    }

    /**
     * @param  array<array<string>>|ArrayIterator<array<string>> $resultSet
     *
     * @dataProvider dataProvider
     */
    public function testExtraParams(iterable $resultSet): void
    {
        $list = (new LoopGen())(
            $resultSet,
            FakeUser::class,
            ['date' => new DateTime('now')]
        );
        foreach ($list as $item) {
            $this->assertInstanceOf(DateTime::class, $item->date);
        }
    }
}
