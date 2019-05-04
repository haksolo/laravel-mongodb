<?php

namespace Extended\MongoDB\Database\Aggregation\Stages;

use Extended\MongoDB\Database\Aggregation\FieldExpression;
use Illuminate\Contracts\Support\Arrayable;

class AddFields implements Arrayable
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

        return ['$addFields' => $specifications];
    }
}
