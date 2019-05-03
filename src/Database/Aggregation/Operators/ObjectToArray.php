<?php

namespace Khronos\MongoDB\Database\Aggregation\Operators;

use Khronos\MongoDB\Database\Aggregation\OperatorExpression;

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
