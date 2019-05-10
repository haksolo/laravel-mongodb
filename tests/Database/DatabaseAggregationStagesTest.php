<?php

namespace Extended\MongoDB\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use Extended\MongoDB\Database\Aggregation\Stages;
use Extended\MongoDB\Database\Aggregation\Expression\Field as FieldExpression;

class DatabaseAggregationStagesTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testAddFields()
    {
        $stage = new Stages\AddFields(['foo' => 'bar']);
        $this->assertEquals(['$addFields' => ['foo' => 'bar']], $stage->toArray());
    }

    public function testCount()
    {
        $stage = new Stages\Count('foo');
        $this->assertEquals(['$count' => 'foo'], $stage->toArray());
    }

    public function testGraphLookup()
    {
        $stage = new Stages\GraphLookup('from', 'startWith', 'connectFromField', 'connectToField', 'as');
        $this->assertEquals(['$graphLookup' => [
            'from' => 'from',
            'startWith' => 'startWith',
            'connectFromField' => 'connectFromField',
            'connectToField' => 'connectToField',
            'as' => 'as',
        ]], $stage->toArray());

        $stage = new Stages\GraphLookup('from', 'startWith', 'connectFromField', 'connectToField', 'as', 'maxDepth', 'depthField', 'restrictSearchWithMatch');
        $this->assertEquals(['$graphLookup' => [
            'from' => 'from',
            'startWith' => 'startWith',
            'connectFromField' => 'connectFromField',
            'connectToField' => 'connectToField',
            'as' => 'as',
            'maxDepth' => 'maxDepth',
            'depthField' => 'depthField',
            'restrictSearchWithMatch' => 'restrictSearchWithMatch',
        ]], $stage->toArray());

        $stage = new Stages\GraphLookup('from', function ($expression) {
            $this->assertInstanceOf(FieldExpression::class, $expression);
            $this->assertEquals('$connectToField', $expression->toValue());
            return 'startWith';
        }, 'connectFromField', 'connectToField', 'as');
        $this->assertEquals(['$graphLookup' => [
            'from' => 'from',
            'startWith' => 'startWith',
            'connectFromField' => 'connectFromField',
            'connectToField' => 'connectToField',
            'as' => 'as',
        ]], $stage->toArray());
    }

    public function testLookup()
    {
        $stage = new Stages\Lookup('from', 'localField', 'foreignField');
        $this->assertEquals(['$lookup' => [
            'from' => 'from',
            'localField' => 'localField',
            'foreignField' => 'foreignField',
        ]], $stage->toArray());

        $stage = new Stages\Lookup('from', 'localField', 'foreignField', 'as');
        $this->assertEquals(['$lookup' => [
            'from' => 'from',
            'localField' => 'localField',
            'foreignField' => 'foreignField',
            'as' => 'as',
        ]], $stage->toArray());
    }

    public function testGroup()
    {
        $stage = new Stages\Group(['foo' => 'bar']);
        $this->assertEquals(['$group' => ['_id' => null, 'foo' => 'bar']], $stage->toArray());

        $stage = new Stages\Group(['foo' => 'bar', '_id' => 1]);
        $this->assertEquals(['$group' => ['_id' => 1, 'foo' => 'bar']], $stage->toArray());
    }

    public function testMatch()
    {
        $stage = new Stages\Match(['foo' => 'bar', 'bar' => 'baz']);
        $this->assertEquals(['$match' => (object) ['foo' => 'bar', 'bar' => 'baz']], $stage->toArray());
    }

    public function testProject()
    {
        $stage = new Stages\Project(['foo' => 'bar']);
        $this->assertEquals(['$project' => ['foo' => 'bar']], $stage->toArray());
    }

    public function testRedact()
    {
        $stage = new Stages\Redact('foo');
        $this->assertEquals(['$redact' => 'foo'], $stage->toArray());
    }

    public function testUnwind()
    {
        $stage = new Stages\Unwind('path');
        $this->assertIsArray($array = $stage->toArray());
        $this->assertEquals(['$unwind' => ['path' => '$path']], $array);
    }

    protected function getMockField($return)
    {
        $field = Mockery::mock(stdClass::class);
        $field->shouldReceive('toArray')->andReturn($return);
        return $field;
    }
}
