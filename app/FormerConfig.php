<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class FormerConfig extends Authenticatable
{
    use Notifiable;
    protected $table = 'former_config';
    //
    protected $guarded = [];

    public $timestamps = false;
}
