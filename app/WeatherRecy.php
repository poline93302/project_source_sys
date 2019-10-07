<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class WeatherRecy extends Authenticatable
{
    use Notifiable;
    protected $table = 'weatherRecy';
    //
    protected $guarded = [];
}
