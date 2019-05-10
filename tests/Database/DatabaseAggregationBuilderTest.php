<?php

namespace Extended\MongoDB\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use Extended\MongoDB\Database\Aggregation\Builder as AggregationBuilder;
use Extended\MongoDB\Database\Connection;
use Illuminate\Support\Collection;

class DatabaseAggregationBuilderTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testBasicGet()
    {
        $connection = Mockery::mock(Connection::class);
        $connection->shouldReceive('aggregate')->with(['collection' => 'users', 'pipeline' => []])->andReturn([['foo']]);
        $builder = $this->getBuilder($connection);

        $collection = $builder->from('users')->get();

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals([['foo']], $builder->get()->toArray());
    }

    public function testReturnsFirstResult()
    {
        $connection = Mockery::mock(Connection::class);
        $connection->shouldReceive('aggregate')->andReturn([['foo'], ['bar'], ['baz']]);
        $builder = $this->getBuilder($connection);

        $this->assertIsArray($array = $builder->first());
        $this->assertEquals(['foo'], $array);
    }

    public function testAddingStage()
    {
        $connection = Mockery::mock(Connection::class);
        $builder = $this->getBuilder($connection);
        $this->assertIsArray($builder->getPipeline());
        $this->assertCount(0, $builder->getPipeline());

        $builder->addStage($this->getMockStage(['foo']));
        $this->assertCount(1, $builder->getPipeline());
        $this->assertEquals([['foo']], $builder->getPipeline());

        $builder->addStage($this->getMockStage(['bar']));
        $this->assertEquals([['foo'], ['bar']], $builder->getPipeline());
    }

    public function testMagicMethodCallResolvesStage()
    {
        $connection = Mockery::mock(Connection::class);
        $builder = $this->getBuilder($connection);

        $builder->test(['foo', 'bar', 'baz']);
        $this->assertCount(1, $pipeline = $builder->getPipeline());
        $this->assertEquals(['foo', 'bar', 'baz'], $pipeline[0]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid NotExistingStage stage.');
        $builder->notExistingStage();
    }

    protected function getBuilder($connection)
    {
        return new AggregationBuilder($connection);
    }

    protected function getMockStage($return = [])
    {
        $stage = Mockery::mock(stdClass::class);
        $stage->shouldReceive('toArray')->once()->andReturn($return);
        return $stage;
    }
}
