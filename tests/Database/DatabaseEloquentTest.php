<?php

namespace Extended\MongoDB\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use Extended\MongoDB\Database\Connection;
use Extended\MongoDB\Database\Eloquent\Model;
use Extended\MongoDB\Database\Eloquent\Builder;
use Extended\MongoDB\Database\Query\Builder as QueryBuilder;

class DatabaseEloquentTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testIfUsingQueryBuilder()
    {
        $connection = $this->getMockConnection();
        $model = Mockery::mock(Model::class.'[getConnection]');
        $model->shouldReceive('getConnection')->once()->andReturn($connection);

        $this->assertInstanceOf(Builder::class, $builder = $model->newModelQuery());
        $this->assertInstanceOf(QueryBuilder::class, $builder->getQuery());
    }

    public function testQualifyColumnRemovesTablePrefix()
    {
        $this->assertEquals('column', (new EloquentModelStub)->qualifyColumn('column'));
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
}

class EloquentModelStub extends Model
{

}
