<?php

namespace Khronos\MongoDB\Database\Aggregation\Stages;

use Khronos\MongoDB\Database\Aggregation\FieldExpression;
use Illuminate\Contracts\Support\Arrayable;

class Project implements Arrayable
{
    protected $specifications;

    public function __construct($specifications)
    {
        $this->specifications = $specifications;
    }

    public function toArray()
    {
        $specifications = [];
        foreach ($this->specifications as $field => $specification) {
            $specifications[$field] = (new FieldExpression($specification, $field))->toArray();
        }

        return ['$project' => $specifications];
    }
}
