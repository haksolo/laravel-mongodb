<?php

namespace Extendend\MongoDB\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
// use Extended\MongoDB\Database\Connection;
use Extended\MongoDB\Database\Eloquent\Model;
use Extended\MongoDB\Database\Eloquent\Builder;
use Extended\MongoDB\Database\Query\Builder as QueryBuilder;

class DatabaseEloquentBuilderTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testModelIfUsingBuilder()
    {
        $model = new EloquentModelStub;

        $this->assertInstanceOf(Builder::class, $model->newEloquentBuilder($this->getMockQueryBuilder()));
    }

    public function testBuilderIfUsingQueryBuilder()
    {
        $builder = $this->getBuilder();

        $this->assertInstanceOf(QueryBuilder::class, $builder->getQuery());
    }

    protected function getBuilder()
    {
        return new Builder($this->getMockQueryBuilder());
    }

    protected function getMockQueryBuilder()
    {
        return Mockery::mock(QueryBuilder::class);
    }
}

class EloquentModelStub extends Model
{

}
