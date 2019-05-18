<?php

namespace Extended\MongoDB\Database\Aggregation\Operators;

use Extended\MongoDB\Database\Aggregation\Expression\Operator as OperatorExpression;

class Sum extends OperatorExpression
{
    protected $expressions = [];

    public function __construct($expressions)
    {
        $this->expressions = $expressions;
    }

    protected function expression()
    {
        return $this->expressions;
    }
}
