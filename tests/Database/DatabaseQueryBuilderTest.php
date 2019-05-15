<?php

namespace Extended\MongoDB\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use Extended\MongoDB\Database\Connection;
use Extended\MongoDB\Database\Query\Builder;
use Extended\MongoDB\Database\Aggregation\Builder as AggregationBuilder;
use Illuminate\Support\Collection;

class DatabaseQueryBuilderTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    /*public function testGetDefault()
    {
        $connection = $this->getMockConnection();
        $connection->shouldReceive('select')->once()->andReturn(['foo']);

        $builder = $this->getBuilder($connection);
        $collection = $builder->from('users')->get();

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals(['foo'], $collection->toArray());
    }*/

    /*public function testWhereAddsArgumentsToFilter()
    {
        $builder = $this->getBuilder();
        $this->assertIsNotArray($builder->filter);

        $builder->where('first');
        $this->assertIsArray($builder->filter);
        $this->assertEquals([['first']], $builder->filter);

        $builder->where('second');
        $this->assertEquals([
            ['first'], ['second']
        ], $builder->filter);

        $builder->where('first', 'second');
        $this->assertEquals([
            ['first'],
            ['second'],
            ['first', 'second']
        ], $builder->filter);

        $builder->where('first', 'second', 'third');
        $this->assertEquals([
            ['first'],
            ['second'],
            ['first', 'second'],
            ['first', 'second', 'third']
        ], $builder->filter);

        $builder->where('first', 'second', 'third', 'fourth');
        $this->assertEquals([
            ['first'],
            ['second'],
            ['first', 'second'],
            ['first', 'second', 'third'],
            ['first', 'second', 'third', 'fourth']
        ], $builder->filter);
    }*/

    public function testInsertGetIdCallsInsertInConnection()
    {
        $result = Mockery::mock(stdClass::class);
        $result->shouldReceive('getInsertedId')->once()->andReturn(1234);

        $connection = $this->getMockConnection();
        $connection->shouldReceive('insert')->with(['collection' => 'users', 'document' => ['foo' => 'bar']])->andReturn($result);

        $builder = $this->getBuilder($connection);
        $this->assertEquals(1234, $builder->from('users')->insertGetId(['foo' => 'bar']));
    }

    /*public function testAggregate()
    {
        $builder = $this->getBuilderWithAggregate((object) ['aggregate' => 100]);
        $this->assertEquals(100, $builder->aggregate('sum'));

        $builder = $this->getBuilderWithAggregate([]);
        $this->assertNull($builder->aggregate('sum'));
    }*/

    public function testAggregationReturnsBuilder()
    {
        $builder = $this->getBuilder();
        $this->assertInstanceOf(AggregationBuilder::class, $builder->aggregation());
    }

    public function testTruncateCallsTruncateInConnection()
    {
        $connection = $this->getMockConnection();
        $connection->shouldReceive('truncate')->with('users');
        $builder = $this->getBuilder($connection);
        $this->assertNull($builder->from('users')->truncate());
    }

    public function testCount()
    {
        $connection = $this->getMockConnection();
        $builder = Mockery::mock(Builder::class.'[sum]', [$connection]);
        $builder->shouldReceive('sum')->once()->andReturn(100);
        $this->assertEquals(100, $builder->count());
    }

    protected function getBuilderWithAggregate($return = [])
    {
        $query = Mockery::mock(stdClass::class);
        $query->shouldReceive('select')->andReturnSelf();
        $query->shouldReceive('sum')->andReturn('foo');

        $aggregation = Mockery::mock(stdClass::class);
        $aggregation->shouldReceive('match')->andReturnSelf();
        $aggregation->shouldReceive('group')->andReturnUsing(function ($expression) use ($aggregation, $query) {
            $this->assertArrayHasKey('aggregate', $expression);
            $this->assertIsCallable($expression['aggregate']);
            $this->assertEquals('foo', $expression['aggregate']($query));
            return $aggregation;
        });
        $aggregation->shouldReceive('get')->andReturn(
            new Collection(array_filter([$return]))
        );

        $connection = $this->getMockConnection();
        $builder = Mockery::mock(Builder::class.'[aggregation]', [$connection]);
        $builder->shouldReceive('aggregation')->andReturn($aggregation);
        return $builder;
    }

    /*public function _testGetCallsSelectWithQueryAndOptions()
    {
        $connection = Mockery::spy(Connection::class);
        $builder = $this->getBuilder($connection);

        $query = $this->getQueryOptionsQuery('users');
        $options = $this->getQueryOptionsOptions();

        $this->assertInstanceOf(Collection::class, $builder->from('users')->get());
        $connection->shouldHaveReceived('select')->with($query, $options);
    }*/

    /*public function _testBasicQueryOptionsWithoutAggregate()
    {
        $builder = $this->getBuilder();
        $array = $builder->from('users')/*->select(['column'])*\/->limit(10)->skip(5)->sort('first', 1)->toArray(['column']);
        $this->assertCount(3, $array);

        [$method, $query, $options] = $array;
        $this->assertEquals('select', $method);
        $this->assertEquals($this->getQueryOptionsQuery('users'), $query);
        $this->assertEquals($this->getQueryOptionsOptions(['column'], 10, 5, ['first' => 1]), $options);
    }*/

    /*public function _testWhere()
    {
        $builder = $this->getBuilder();
        $builder/*->select('*')*\/->from('users')->where('id', '=', 1);

        [$method, $query, $options] = $builder->toArray();
        $this->assertArrayHasKey('filter', $query);
        $this->assertEquals(['$and' => [['id' => ['$eq' => 1]]]], $query['filter']);
    }*/

    /*public function testLimitOrTake()
    {
        $builder = $this->getBuilder();
        $builder->limit(10);
        $this->assertEquals(10, $builder->limit);

        $builder->take(5);
        $this->assertEquals(5, $builder->limit);
    }*/

    /*public function testSkipOrOffset()
    {
        $builder = $this->getBuilder();
        $builder->offset(10);
        $this->assertEquals(10, $builder->offset);

        $builder->skip(5);
        $this->assertEquals(5, $builder->offset);

        $builder->offset(-10);
        $this->assertEquals(0, $builder->offset);
    }*/

    /*public function _testSortAndOrderBy()
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
    }*/

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
        $grammar = Mockery::mock(stdClass::class);
        $processor = Mockery::mock(stdClass::class);

        $connection = Mockery::mock(Connection::class);
        $connection->shouldReceive('getQueryGrammar')->once()->andReturn($grammar);
        $connection->shouldReceive('getPostProcessor')->once()->andReturn($processor);

        return $connection;
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
