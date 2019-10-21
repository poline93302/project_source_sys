<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FarmerInfo extends Model
{
    protected $table = 'farmerInfo_view';
//    黑名單 為空 ＊＊＊
    protected $guarded = [];
    public $timestamps = false;
}
