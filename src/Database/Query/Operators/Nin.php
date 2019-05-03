<?php

namespace Khronos\MongoDB\Database\Query\Operators;

use Khronos\MongoDB\Database\Query\OperatorExpression;

class Nin extends OperatorExpression
{
    protected $values = [];

    /*public function __construct($field, $values)
    {
        $this->field = $field;

        $this->values = $values;
    }*/
}
