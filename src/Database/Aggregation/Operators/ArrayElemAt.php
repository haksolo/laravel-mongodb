<?php

namespace Extended\MongoDB\Database\Aggregation\Operators;

use Extended\MongoDB\Database\Aggregation\OperatorExpression;

class ArrayElemAt extends OperatorExpression
{
    protected $array;

    protected $index;

    public function __construct($array, $index)
    {
        $this->array = $array;

        $this->index = $index;
    }

    protected function expression()
    {
        return [$this->array, $this->index];
    }
}
