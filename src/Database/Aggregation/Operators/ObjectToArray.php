<?php

namespace Extended\MongoDB\Database\Aggregation\Operators;

use Extended\MongoDB\Database\Aggregation\OperatorExpression;

class ObjectToArray extends OperatorExpression
{
    protected $object;

    public function __construct($object)
    {
        $this->object = $object;
    }

    protected function expression()
    {
        return $this->object;
    }
}
