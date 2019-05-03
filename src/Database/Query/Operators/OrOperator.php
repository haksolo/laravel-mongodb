<?php

namespace Khronos\MongoDB\Database\Query\Operators;

use Khronos\MongoDB\Database\Query\OperatorExpression;
use Khronos\MongoDB\Contracts\Database\LogicalOperator;

class OrOperator extends OperatorExpression implements LogicalOperator
{
    protected $operator = '$or';

    protected $expressions = [];

    public function __construct($field = null, ...$expressions)
    {
        $this->field = $field;

        $this->expressions = $expressions;
    }

    public function append($expression)
    {
        $this->expressions[] = $expression;

        return $this;
    }

    protected function expression()
    {
        return [$this->operator() => $this->expressions];
    }
}
