<?php

namespace Extended\MongoDB\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use Extended\MongoDB\Database\Aggregation\Expression\Field as FieldExpression;

class DatabaseAggregationExpressionFieldTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testBasicUsage()
    {
        $field = new FieldExpression(null, 'path_to_field');
        $this->assertEquals('$path_to_field', $field);
        $this->assertEquals('$path_to_field', (string) $field);
        $this->assertEquals('$path_to_field', $field->toValue());
    }

    public function testWithCustomPrefix()
    {
        $field = new FieldExpression(null, 'path_to_field', '$$');
        $this->assertEquals('$$path_to_field', $field);

        $field = new FieldExpression(null, 'path_to_field', null);
        $this->assertEquals('path_to_field', $field);
    }

    public function testPropertyAccess()
    {
        $first = new FieldExpression(null, 'first');
        $this->assertEquals('$first', $first);

        $second = $first->second;
        $this->assertInstanceOf(FieldExpression::class, $second);
        $this->assertNotSame($first, $second);
        $this->assertEquals('$first.second', $second);

        $this->assertEquals('$first.second.third', $second->third);
        $this->assertEquals('$first.second.third', $first->second->third);
    }

    public function testHelperMethods()
    {
        $person = new FieldExpression(null, 'person');
        $this->assertEquals('$$ROOT', $person->root());
        $this->assertEquals('$age', $person->select('age'));
        $this->assertEquals('i am string', $person->input('i am string'));
        $this->assertEquals(12345, $person->input(12345)->toValue());
    }

    public function testBaseReturnsItself()
    {
        $expression = new FieldExpressionWithCustomNamespaceStub(null, 'foo');
        $this->assertEquals(
            $expression->toValue(),
            $expression->fieldExpressionOperatorStub()->base
        );
    }

    public function testParametersPassedOnExpressionClosure()
    {
        $expression = new FieldExpression(function ($field) {
            $this->assertInstanceOf(FieldExpression::class, $field);
            $this->assertEquals('$foo', $field);
            return [];
        }, 'foo');
        $this->assertEquals([], $expression->toArray());
    }
}

class FieldExpressionWithCustomNamespaceStub extends FieldExpression
{
    protected static $namespace = __NAMESPACE__;
}

class FieldExpressionOperatorStub
{
    public $base;

    public function __construct($base)
    {
        $this->base = $base;
    }

    public function factory($base)
    {
        return new static($base);
    }
}
