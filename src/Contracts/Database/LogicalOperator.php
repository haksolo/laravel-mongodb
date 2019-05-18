<?php

namespace Extended\MongoDB\Contracts\Database;

interface LogicalOperator
{
    public function append($expression);
}
