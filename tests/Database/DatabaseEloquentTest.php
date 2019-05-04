<?php

namespace Extended\MongoDB\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use Khronos\MongoDB\Database\Connection;
use Khronos\MongoDB\Database\Eloquent\Model;
use Khronos\MongoDB\Database\Eloquent\Builder;
use Khronos\MongoDB\Database\Query\Builder as QueryBuilder;

class DatabaseEloquentTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testIfUsingQueryBuilder()
    {
        $connection = Mockery::mock(Connection::class);
        $model = Mockery::mock(Model::class.'[getConnection]');
        $model->shouldReceive('getConnection')->once()->andReturn($connection);

        $this->assertInstanceOf(Builder::class, $builder = $model->newModelQuery());
        $this->assertInstanceOf(QueryBuilder::class, $builder->getQuery());
    }

    public function testQualifyColumnRemovesTablePrefix()
    {
        $this->assertEquals('column', (new EloquentModelStub)->qualifyColumn('column'));
    }
}

class EloquentModelStub extends Model
{

}
