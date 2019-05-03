<?php

namespace Extended\MongoDB\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use Khronos\MongoDB\Database\Connection;

class DatabaseConnectionTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testSelect()
    {
        $collection = Mockery::mock(stdClass::class);
        $collection->shouldReceive('find')->once()->andReturn([['foo']]);

        $client = Mockery::mock(stdClass::class);
        $client->shouldReceive('selectCollection')->once()->andReturn($collection);

        $connection = $this->getMockBuilder(Connection::class)
            ->setMethods(null)
            ->setConstructorArgs([$client])
            ->getMock();

        // $connection->expects($this->once())->method('table')->will($this->returnValue(1));

        $this->assertEquals([['foo']], $connection->select([]));
    }

    protected function getMockConnection()
    {
        $connection = $this->getMockBuilder(Connection::class)
            ->setMethods(['select'])
            ->setConstructorArgs([$client])
            ->getMock();

        return $connection;
    }
}
