<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormerInfo extends Model
{
    protected $table = 'Former_Corr';
//    黑名單 為空 ＊＊＊
    protected $guarded = [];
    public $timestamps = false;
}
