<?php

namespace Extended\MongoDB\Tests;

use PHPUnit\Framework\TestCase;
use Extended\MongoDB\Database\Aggregation\Operators;
use Extended\MongoDB\Database\Aggregation\Expression\Field as FieldExpression;

class DatabaseAggregationOperatorsTest extends TestCase
{
    public function testAdd()
    {
        $operator = new Operators\Add(10, 5);
        $this->assertEquals(['$add' => [10, 5]], $operator->toArray());
    }

    public function testAnd()
    {
        $operator = new Operators\AndOperator(...[1, 2, 3, 4]);
        $this->assertEquals(['$and' => [1, 2, 3, 4]], $operator->toArray());
    }

    public function testArrayElemAt()
    {
        $operator = new Operators\ArrayElemAt('array', 'index');
        $this->assertEquals(['$arrayElemAt' => ['array', 'index']], $operator->toArray());
    }

    public function testAvg()
    {
        $operator = new Operators\Avg('expression');
        $this->assertEquals(['$avg' => 'expression'], $operator->toArray());
    }

    public function testCond()
    {
        $operator = new Operators\Cond('if', 'then', 'else');
        $this->assertEquals(['$cond' => ['if', 'then', 'else']], $operator->toArray());
    }

    public function testDayOfWeek()
    {
        $operator = new Operators\DayOfWeek('expression');
        $this->assertEquals(['$dayOfWeek' => 'expression'], $operator->toArray());
    }

    public function testDivide()
    {
        $operator = new Operators\Divide(10, 5);
        $this->assertEquals(['$divide' => [10, 5]], $operator->toArray());
    }

    public function testEq()
    {
        $operator = new Operators\Eq(10, 5);
        $this->assertEquals(['$eq' => [10, 5]], $operator->toArray());
    }

    public function testFilter()
    {
        $operator = new Operators\Filter('people', [1, 2, 3, 4]);
        $this->assertEquals(['$filter' => ['input' => 'people', 'as' => 'this', 'cond' => [1, 2, 3, 4]]], $operator->toArray());

        $operator = new Operators\Filter('people', [1, 2, 3, 4], 'person');
        $this->assertEquals(['$filter' => ['input' => 'people', 'as' => 'person', 'cond' => [1, 2, 3, 4]]], $operator->toArray());

        $operator = new Operators\Filter('people', function ($item) {
            $this->assertInstanceOf(FieldExpression::class, $item);
            $this->assertEquals('$$item', $item);
            return $item;
        }, 'person');
        $this->assertEquals(['$filter' => ['input' => 'people', 'as' => 'item', 'cond' => '$$item']], $operator->toArray());

        $operator = new Operators\Filter('people', function () { return []; }, 'person');
        $this->assertEquals(['$filter' => ['input' => 'people', 'as' => 'person', 'cond' => []]], $operator->toArray());
    }

    public function testIfNull()
    {
        $operator = new Operators\IfNull('expression', 'replacement');
        $this->assertEquals(['$ifNull' => ['expression', 'replacement']], $operator->toArray());
    }

    public function testIn()
    {
        $operator = new Operators\In(10, 5);
        $this->assertEquals(['$in' => [10, 5]], $operator->toArray());
    }

    public function testIsArray()
    {
        $operator = new Operators\IsArray('expression');
        $this->assertEquals(['$isArray' => 'expression'], $operator->toArray());
    }

    public function testMap()
    {
        $operator = new Operators\Map('people', [1, 2, 3, 4]);
        $this->assertEquals(['$map' => ['input' => 'people', 'as' => 'this', 'in' => [1, 2, 3, 4]]], $operator->toArray());

        $operator = new Operators\Map('people', [1, 2, 3, 4], 'person');
        $this->assertEquals(['$map' => ['input' => 'people', 'as' => 'person', 'in' => [1, 2, 3, 4]]], $operator->toArray());

        $operator = new Operators\Map('people', function ($item) {
            $this->assertInstanceOf(FieldExpression::class, $item);
            $this->assertEquals('$$item', $item);
            return $item;
        }, 'person');
        $this->assertEquals(['$map' => ['input' => 'people', 'as' => 'item', 'in' => '$$item']], $operator->toArray());

        $operator = new Operators\Map('people', function () { return []; }, 'person');
        $this->assertEquals(['$map' => ['input' => 'people', 'as' => 'person', 'in' => []]], $operator->toArray());
    }

    public function testMax()
    {
        $operator = new Operators\Max('expression');
        $this->assertEquals(['$max' => 'expression'], $operator->toArray());
    }

    public function testMin()
    {
        $operator = new Operators\Min('expression');
        $this->assertEquals(['$min' => 'expression'], $operator->toArray());
    }

    public function testMod()
    {
        $operator = new Operators\Mod(10, 5);
        $this->assertEquals(['$mod' => [10, 5]], $operator->toArray());
    }

    public function testNot()
    {
        $operator = new Operators\Not('expression');
        $this->assertEquals(['$not' => 'expression'], $operator->toArray());
    }

    public function testObjectToArray()
    {
        $operator = new Operators\ObjectToArray('expression');
        $this->assertEquals(['$objectToArray' => 'expression'], $operator->toArray());
    }

    public function testReduce()
    {
        $operator = new Operators\Reduce(1, 2, []);
        $this->assertEquals(['$reduce' => ['input' => 1, 'initialValue' => 2, 'in' => []]], $operator->toArray());

        $operator = Operators\Reduce::factory(1, 2);
        $this->assertEquals(['$reduce' => ['input' => 1, 'initialValue' => [], 'in' => 2]], $operator->toArray());

        $operator = Operators\Reduce::factory(1, function ($carry, $item) {
            $this->assertInstanceOf(FieldExpression::class, $carry);
            $this->assertEquals('$$this', $carry);
            $this->assertInstanceOf(FieldExpression::class, $item);
            $this->assertEquals('$$value', $item);
            return [];
        });
        $this->assertEquals(['$reduce' => ['input' => 1, 'initialValue' => [], 'in' => []]], $operator->toArray());
    }

    public function testSetUnion()
    {
        $operator = new Operators\SetUnion(...[1, 2, 3, 4]);
        $this->assertEquals(['$setUnion' => [1, 2, 3, 4]], $operator->toArray());
    }

    public function testSubtract()
    {
        $operator = new Operators\Subtract(10, 5);
        $this->assertEquals(['$subtract' => [10, 5]], $operator->toArray());
    }

    public function testSum()
    {
        $operator = new Operators\Sum([1, 2, 3, 4]);
        $this->assertEquals(['$sum' => [1, 2, 3, 4]], $operator->toArray());
    }

    public function testToDate()
    {
        $operator = new Operators\ToDate('expression');
        $this->assertEquals(['$toDate' => 'expression'], $operator->toArray());
    }

    public function testToInt()
    {
        $operator = new Operators\ToInt('expression');
        $this->assertEquals(['$toInt' => 'expression'], $operator->toArray());
    }

    public function testToString()
    {
        $operator = new Operators\ToString('expression');
        $this->assertEquals(['$toString' => 'expression'], $operator->toArray());
    }
}
