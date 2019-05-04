<?php

namespace Extended\MongoDB\Test;

use Mockery;
use PHPUnit\Framework\TestCase;
// use PHPUnit\Framework\Error\Error;
// use Khronos\MongoDB\Contracts\Database\LogicalOperator;
use Khronos\MongoDB\Database\Query\FilterExpression;
use Khronos\MongoDB\Database\Query\Operators\Eq;
use Khronos\MongoDB\Database\Query\Operators\AndOperator;
use Khronos\MongoDB\Contracts\Database\LogicalOperator;

class DatabaseQueryFilterExpressionTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testResolvesOperator()
    {
        $operator = FilterExpression::resolve('eq', 'field', 'value');
        $this->assertInstanceOf(Eq::class, $operator);

        $this->expectException(\Error::class);
        $operator = FilterExpression::resolve('not-existing-operator', 'field', 'value');
    }

    public function testResolvesOperatorThatIsReservedWord()
    {
        $operator = FilterExpression::resolve('and', 'field', 'value');
        $this->assertInstanceOf(AndOperator::class, $operator);
    }

    public function testAppend()
    {
        $logical = Mockery::mock(AndOperator::class);
        $logical->shouldReceive('operator')->andReturn('$and');
        $logical->shouldReceive('append');

        $filter = new FilterExpression($logical);

        $this->assertSame($filter, $filter->append('filter'));
        $this->assertInstanceOf(AndOperator::class, $filter->operator());

        $this->assertInstanceOf(AndOperator::class, $filter->operator());
        $filter->append('filter', 'and');
        $this->assertInstanceOf(AndOperator::class, $filter->operator());

        $filter->append('filter', 'or');
        $this->assertNotInstanceOf(AndOperator::class, $filter->operator());
    }
}
