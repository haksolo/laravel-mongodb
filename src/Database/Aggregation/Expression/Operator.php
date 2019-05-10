<?php

namespace Extended\MongoDB\Database\Aggregation\Expression;

use Extended\MongoDB\Database\Aggregation\Expression;
use Illuminate\Support\Str;

class Operator extends Expression
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
