<?php

namespace Extended\MongoDB\Database\Aggregation\Operators;

use Extended\MongoDB\Database\Aggregation\Expression\Field as FieldExpression;
use Extended\MongoDB\Database\Aggregation\Expression\Operator as OperatorExpression;

class Reduce extends OperatorExpression
{
    protected $input;

    protected $initialValue;

    protected $in;

    public function __construct($input, $initialValue, $in)
    {
        $this->input = $input;

        $this->initialValue = $initialValue;

        $this->in = $in;
    }

    public static function factory(...$parameters)
    {
        [$input, $in, $initialValue] = array_pad($parameters, 3, []);

        return new static($input, $initialValue, $in);
    }

    protected function expression()
    {
        return ['input' => $this->input, 'initialValue' => $this->initialValue, 'in' => $this->in];
    }

    protected function parameters()
    {
        return [new FieldExpression(null, 'this', '$$'), new FieldExpression(null, 'value', '$$')];
    }
}
