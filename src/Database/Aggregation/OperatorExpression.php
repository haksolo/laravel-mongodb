<?php

namespace Extended\MongoDB\Database\Aggregation;

use Illuminate\Support\Str;

class OperatorExpression extends Expression
{
    protected $syntax;

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [$this->syntax() => $this->parse($this->expression())];
    }

    protected function syntax()
    {
        if (! is_null($this->syntax)) {
            return $this->syntax;
        }

        return static::DEFAULT_PREFIX.Str::camel(class_basename($this));
    }

    protected function base()
    {
        return $this->toArray();
    }
}
