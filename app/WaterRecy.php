<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class WaterRecy extends Authenticatable
{
    use Notifiable;
    protected $table = 'waterRecy';
    //
    protected $guarded = [];
}
