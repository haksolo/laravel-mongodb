<?php

namespace Extended\MongoDB\Database\Query\Operators;

use Extended\MongoDB\Database\Query\OperatorExpression;
use Extended\MongoDB\Contracts\Database\LogicalOperator;

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
