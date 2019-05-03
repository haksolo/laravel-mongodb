<?php

namespace Khronos\MongoDB\Database\Aggregation\Operators;

use ReflectionFunction;
use Khronos\MongoDB\Database\Aggregation\FieldExpression;
use Khronos\MongoDB\Database\Aggregation\OperatorExpression;

class Filter extends OperatorExpression
{
    const DEFAULT_AS_VALUE = 'this';

    protected $input;

    protected $as;

    protected $cond;

    public function __construct($input, $cond, $as = self::DEFAULT_AS_VALUE)
    {
        $this->input = $input;

        $this->cond = $cond;

        $this->as = $as;
    }

    protected function expression()
    {
        return ['input' => $this->input, 'as' => $this->as(), 'cond' => $this->cond];
    }

    protected function parameters($parameter = null)
    {
        return new FieldExpression(null, $this->as(), '$$');
    }

    protected function as()
    {
        $reflection = new ReflectionFunction($this->cond);
        if (count($parameters = $reflection->getParameters())
            && $this->as = self::DEFAULT_AS_VALUE) {
            return $parameters[0]->name;
        }

        return $this->as;
    }
}
