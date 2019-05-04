<?php

namespace Extended\MongoDB\Foundation\Auth;

use Extended\MongoDB\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
// use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable;
}
