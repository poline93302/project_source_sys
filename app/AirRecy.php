<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AirRecy extends Authenticatable
{
    use Notifiable;
    protected $table = 'airRecy';
    //
    protected $guarded = [];
}
