<?php

namespace Extended\MongoDB\Database\Aggregation\Operators;

use ReflectionFunction;
use Extended\MongoDB\Database\Aggregation\FieldExpression;
use Extended\MongoDB\Database\Aggregation\OperatorExpression;

class Map extends OperatorExpression
{
    const DEFAULT_AS_VALUE = 'this';

    protected $input;

    protected $as;

    protected $in;

    public function __construct($input, $in, $as = self::DEFAULT_AS_VALUE)
    {
        $this->input = $input;

        $this->in = $in;

        $this->as = $as;
    }

    protected function expression()
    {
        return ['input' => $this->input, 'as' => $this->as(), 'in' => $this->in];
    }

    protected function parameters($parameter = null)
    {
        return new FieldExpression(null, $this->as(), '$$');
    }

    protected function as()
    {
        $reflection = new ReflectionFunction($this->in);
        if (count($parameters = $reflection->getParameters())
            && $this->as = self::DEFAULT_AS_VALUE) {
            return $parameters[0]->name;
        }

        return $this->as;
    }
}
