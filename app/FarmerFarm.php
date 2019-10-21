<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class FarmerFarm extends Authenticatable
{
    use Notifiable;
    protected $table = 'form_crop_correspondence';
    //
    protected $guarded = [];

    public $timestamps = false;
}
