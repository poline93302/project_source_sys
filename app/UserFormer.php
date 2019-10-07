<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserFormer extends Authenticatable
{
    use Notifiable;
    protected $table = 'userFormer';
    //
    protected $fillable = [
        'name', 'username', 'email', 'password',
    ];
    protected $hidden = [
        'password',
    ];
}
