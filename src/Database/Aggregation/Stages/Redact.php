<?php

namespace Khronos\MongoDB\Database\Aggregation\Stages;

use Illuminate\Contracts\Support\Arrayable;

class Redact implements Arrayable
{
    protected $expression;

    public function __construct($expression)
    {
        $this->expression = $expression;
    }

    public function toArray()
    {
        return ['$redact' => $this->expression];
    }
}
