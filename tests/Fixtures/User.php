<?php

namespace Inventas\PasswordCompliance\Tests\Fixtures;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Inventas\PasswordCompliance\Traits\RequiresPasswordChange;

class User extends Authenticatable
{
    use RequiresPasswordChange;

    protected $table = 'users';

    protected $guarded = [];

    public $timestamps = true;
}
