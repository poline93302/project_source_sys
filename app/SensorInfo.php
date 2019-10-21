<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SensorInfo extends Authenticatable
{
    use Notifiable;
    protected $table = 'sensor_info';
    //
    protected $guarded = [];
    public $timestamps = false;
}
