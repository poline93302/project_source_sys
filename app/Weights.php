<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Weights extends Authenticatable
{
    use Notifiable;
    protected $table = 'weights';
    //
    protected $guarded = [];

    public $timestamps = false;
}
