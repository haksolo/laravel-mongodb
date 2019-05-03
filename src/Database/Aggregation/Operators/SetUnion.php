<?php

namespace Khronos\MongoDB\Database\Aggregation\Operators;

use Khronos\MongoDB\Database\Aggregation\OperatorExpression;

class SetUnion extends OperatorExpression
{
    protected $expressions = [];

    public function __construct(...$expressions)
    {
        $this->expressions = $expressions;
    }

    protected function expression()
    {
        return $this->expressions;
    }
}
