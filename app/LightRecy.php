<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class LightRecy extends Authenticatable
{
    use Notifiable;
    protected $table = 'lightRecy';
    //
    protected $guarded = [];
}
