<?php

namespace Extended\MongoDB\Database\Aggregation\Stages;

use Illuminate\Contracts\Support\Arrayable;

class Count implements Arrayable
{
    protected $string;

    public function __construct($string)
    {
        $this->string = $string;
    }

    public function toArray()
    {
        return ['$count' => $this->string];
    }
}
