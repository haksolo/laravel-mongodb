<?php

namespace Extended\MongoDB\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use Extended\MongoDB\Database\Aggregation\Expression;
use Extended\MongoDB\Database\Aggregation\Expression\Field as FieldExpression;
use Illuminate\Contracts\Support\Arrayable;

class DatabaseAggregationExpressionTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testParsingScalar()
    {
        $expression = new Expression('i am string');
        $this->assertEquals('i am string', $expression->toArray());

        $expression = new Expression(12345);
        $this->assertEquals(12345, $expression->toArray());
    }

    public function testParsingArray()
    {
        $expression = new Expression(['i', 'am', 'array']);
        $this->assertEquals(['i', 'am', 'array'], $expression->toArray());

        $expression = new Expression(new ExpressionCustomArrayableStub);
        $this->assertEquals(['yet', 'another', 'array'], $expression->toArray());
    }

    public function testParsingFieldExpression()
    {
        $expression = new Expression(new FieldExpression(null, 'path_to_field'));
        $this->assertEquals('$path_to_field', $expression->toArray());
    }

    public function testParsingClosurePassesParameters()
    {
        $expression = new ExpressionWithParametersStub(function (...$parameters) {
            $this->assertEquals(['array', 'passed', 'as', 'parameters'], $parameters);
        });

        $this->assertEquals(null, $expression->toArray());
    }

    public function testParsingClosureScalar()
    {
        $expression = new Expression(function () {
            return 'i am string';
        });
        $this->assertEquals('i am string', $expression->toArray());

        $expression = new Expression(function () {
            return 12345;
        });
        $this->assertEquals(12345, $expression->toArray());
    }

    public function testParsingClosureArray()
    {
        $expression = new Expression(function () {
            return ['foo', 'bar', 'baz'];
        });
        $this->assertEquals(['foo', 'bar', 'baz'], $expression->toArray());

        $expression = new Expression(function () {
            return new ExpressionCustomArrayableStub;
        });
        $this->assertEquals(['yet', 'another', 'array'], $expression->toArray());
    }

    public function testParsingClosureFieldExpression()
    {
        $expression = new Expression(function () {
            return new FieldExpression(null, 'path_to_field');
        });
        $this->assertEquals('$path_to_field', $expression->toArray());
    }

    public function testMagicMethodCallResolveOperator()
    {
        $expression = new ExpressionWithCustomNamespaceStub;
        $expression->expressionCustomOperatorStub();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid expression method nonExistingOperator.');
        $expression->__call('nonExistingOperator', []);
    }

    public function testMagicMethodCallMacroCall()
    {
        Expression::macro('testMacro', function () {
            return $this;
        });
        $expression = new Expression;
        $this->assertSame($expression, $expression->testMacro());
    }

    public function testFactoryCreatingInstance()
    {
        $this->assertEquals(
            (new Expression('expression'))->toArray(),
            Expression::factory('expression')->toArray()
        );
    }

    public function testBaseReturnsAnExpression()
    {
        $expression = new ExpressionWithCustomNamespaceStub('expression');
        $operator = $expression->expressionCustomOperatorStub();
        $this->assertEquals($expression->toArray(), $operator->toArray());
    }
}

class ExpressionWithParametersStub extends Expression
{
    protected function parameters()
    {
        return ['array', 'passed', 'as', 'parameters'];
    }
}

class ExpressionCustomArrayableStub implements Arrayable
{
    public function toArray()
    {
        return ['yet', 'another', 'array'];
    }
}

class ExpressionWithCustomNamespaceStub extends Expression
{
    protected static $namespace = __NAMESPACE__;
}

class ExpressionCustomOperatorStub extends Expression
{
}

class ExpressionCustomOperatorStubOperator extends Expression
{
}
