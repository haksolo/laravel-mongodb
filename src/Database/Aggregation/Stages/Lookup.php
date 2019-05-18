<?php

namespace Extended\MongoDB\Database\Aggregation\Stages;

use Illuminate\Contracts\Support\Arrayable;

class Lookup implements Arrayable
{
    protected $from;

    protected $localField;

    protected $foreignField;

    protected $as;

    public function __construct($from, $localField, $foreignField, $as = null)
    {
        $this->from = $from;

        $this->localField = $localField;

        $this->foreignField = $foreignField;

        $this->as = $as;
    }

    public function toArray()
    {
        return ['$lookup' => array_filter([
            'from' => $this->from,
            'localField' => $this->localField,
            'foreignField' => $this->foreignField,
            'as' => $this->as
        ])];
    }
}
