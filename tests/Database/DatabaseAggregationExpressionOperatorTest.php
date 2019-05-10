<?php

namespace Extended\MongoDB\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use Extended\MongoDB\Database\Aggregation\Expression\Operator as OperatorExpression;

class DatabaseAggregationExpressionOperatorTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testDefault()
    {
        $operator = new OperatorExpression('expression');
        $this->assertEquals(['$operator' => 'expression'], $operator->toArray());
    }

    public function testWithCustomSyntax()
    {
        $operator = new OperatorExpressionWithCustomSyntaxStub('expression');
        $this->assertEquals(['$customSyntax' => 'expression'], $operator->toArray());
    }

    public function testBaseReturnsToArray()
    {
        $operator = new OperatorExpressionWithCustomNamespaceStub('expression');
        $this->assertEquals(
            $operator->toArray(),
            $operator->operatorExpressionCustomOperatorStub()->base
        );
    }
}

class OperatorExpressionWithCustomSyntaxStub extends OperatorExpression
{
    protected $syntax = '$customSyntax';
}

class OperatorExpressionWithCustomNamespaceStub extends OperatorExpression
{
    protected static $namespace = __NAMESPACE__;
}

class OperatorExpressionCustomOperatorStub
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
