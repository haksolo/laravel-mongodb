<?php

namespace Extended\MongoDB\Database\Query\Operators;

use Extended\MongoDB\Database\Query\OperatorExpression;

class Nin extends OperatorExpression
{
    protected $values = [];

    /*public function __construct($field, $values)
    {
        $this->field = $field;

        $this->values = $values;
    }*/
}
