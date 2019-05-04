<?php

namespace Extended\MongoDB\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use Khronos\MongoDB\Database\Connection;
use Khronos\MongoDB\Database\Query\Builder;
use Illuminate\Support\Collection;

class DatabaseQueryBuilderTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testBasicGet()
    {
        $connection = $this->getMockConnection();
        $connection->shouldReceive('select')->once()->andReturn(['foo']);

        $builder = $this->getBuilder($connection);
        $collection = $builder->from('users')->get();

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals(['foo'], $collection->toArray());
    }

    public function testGetCallsSelectWithQueryAndOptions()
    {
        $connection = Mockery::spy(Connection::class);
        $builder = $this->getBuilder($connection);

        $query = $this->getQueryOptionsQuery('users');
        $options = $this->getQueryOptionsOptions();

        $this->assertInstanceOf(Collection::class, $builder->from('users')->get());
        $connection->shouldHaveReceived('select')->with($query, $options);
    }

    public function testBasicQueryOptionsWithoutAggregate()
    {
        $builder = $this->getBuilder();
        $array = $builder->from('users')/*->select(['column'])*/->limit(10)->skip(5)->sort('first', 1)->toArray(['column']);
        $this->assertCount(3, $array);

        [$method, $query, $options] = $array;
        $this->assertEquals('select', $method);
        $this->assertEquals($this->getQueryOptionsQuery('users'), $query);
        $this->assertEquals($this->getQueryOptionsOptions(['column'], 10, 5, ['first' => 1]), $options);
    }

    public function testWhere()
    {
        $builder = $this->getBuilder();
        $builder/*->select('*')*/->from('users')->where('id', '=', 1);

        [$method, $query, $options] = $builder->toArray();
        $this->assertArrayHasKey('filter', $query);
        $this->assertEquals(['$and' => [['id' => ['$eq' => 1]]]], $query['filter']);
    }

    public function testLimitOrTake()
    {
        $builder = $this->getBuilder();
        $builder->limit(10);
        $this->assertEquals(10, $builder->limit);

        $builder->take(5);
        $this->assertEquals(5, $builder->limit);
    }

    public function testSkipOrOffset()
    {
        $builder = $this->getBuilder();
        $builder->offset(10);
        $this->assertEquals(10, $builder->offset);

        $builder->skip(5);
        $this->assertEquals(5, $builder->offset);

        $builder->offset(-10);
        $this->assertEquals(0, $builder->offset);
    }

    public function testSortAndOrderBy()
    {
        $builder = $this->getBuilder();
        $builder->sort('first', 1);
        $this->assertIsArray($builder->sort);
        $this->assertArrayHasKey('first', $builder->sort);
        $this->assertEquals(1, $builder->sort['first']);

        $builder->orderBy('second', 'asc');
        $this->assertIsArray($builder->sort);
        $this->assertArrayHasKey('second', $builder->sort);
        $this->assertEquals(1, $builder->sort['second']);

        $builder->orderBy('third', 'desc');
        $this->assertEquals(-1, $builder->sort['third']);

        $builder->orderBy('fourth', 'not-asc-or-desc');
        $this->assertEquals('not-asc-or-desc', $builder->sort['fourth']);
    }

    /*
    public function testColumns()
    {
        $bulder = $this->getBuilder();

        // $this->assertEquals([], $builder->columns());
    }
    */

    protected function getBuilder($connection = null)
    {
        return new Builder($connection ?: $this->getMockConnection());
    }

    protected function getMockQueryBuilder()
    {
        return Mockery::mock(Builder::class);
    }

    protected function getMockConnection()
    {
        return Mockery::mock(Connection::class);
    }

    protected function getQueryOptionsQuery($collection, $filter = [])
    {
        return compact('collection', 'filter');
    }

    protected function getQueryOptionsOptions($projection = [], $limit = null, $skip = null, $sort = null)
    {
        return compact('projection', 'limit', 'skip', 'sort');
    }
}
