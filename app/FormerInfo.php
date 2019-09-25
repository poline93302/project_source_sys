<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormerInfo extends Model
{
    protected $table = 'formerInfo';
//    黑名單 為空 ＊＊＊
    protected $guarded = [];
    public $timestamps = false;
}
