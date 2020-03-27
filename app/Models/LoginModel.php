<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class LoginModel extends Authenticatable
{

    use HasApiTokens, Notifiable;
    //public $timestamps = false;

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

}
