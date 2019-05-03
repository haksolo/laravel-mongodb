<?php

namespace Khronos\MongoDB\Database\Query;

use Khronos\MongoDB\Contracts\Database\LogicalOperator;
use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Arrayable;

class FilterExpression implements Arrayable
{
    protected $operator;

    protected $implicit = false;

    public function __construct(LogicalOperator $operator)
    {
        $this->operator = $operator;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return call_user_func($this->operator, $this->implicit)->toArray();
    }

    public function append($filter, $logical = 'and')
    {
        if ($this->operator->operator() != '$'.$logical) {
            // create new logical operator
            $this->operator = self::resolve($logical, null, $this->operator);
        }

        $this->operator->append($filter);

        return $this;
    }

    public static function resolve($operator, ...$parameters)
    {
        $operator = __NAMESPACE__.'\\Operators\\'.Str::studly($operator);

        if (class_exists($operator.'Operator')) {
            $operator .= 'Operator';
        }

        return new $operator(...$parameters);
    }

    public function __call($method, $parameters)
    {
        return $this->append(static::resolve($method, null, ...$parameters));
    }

    public function implicit($value)
    {
        $this->implicit = $value;

        return $this;
    }
}
