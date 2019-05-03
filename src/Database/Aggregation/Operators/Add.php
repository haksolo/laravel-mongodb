<?php

namespace Khronos\MongoDB\Database\Aggregation\Operators;

use Khronos\MongoDB\Database\Aggregation\OperatorExpression;

class Add extends OperatorExpression
{
    protected $expression1;

    protected $expression2;

    public function __construct($expression1, $expression2)
    {
        $this->expression1 = $expression1;

        $this->expression2 = $expression2;
    }

    protected function expression()
    {
        return [$this->expression1, $this->expression2];
    }
}
