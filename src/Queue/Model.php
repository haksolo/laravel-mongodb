<?php

namespace Extended\MongoDB\Queue;

use Extended\MongoDB\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    public function getIdAttribute()
    {
        return $this->attributes['_id'];
    }

    public function job($container = null)
    {
        return new Job($container ?: app(), $this);
    }
}
