<?php

namespace Khronos\MongoDB\Database\Eloquent;

use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    use ModelTrait;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = '_id';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = \MongoDB\BSON\ObjectId::class;

    /**
     * The connection resolver instance.
     *
     * @var \Illuminate\Database\ConnectionResolverInterface
     */
    protected static $resolver;

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = null;
}
