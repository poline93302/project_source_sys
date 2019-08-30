<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class FormerConfigController extends Controller
{
    //農夫設定值
    public function create()
    {

    }

    public function delete()
    {

    }

    public function update()
    {

    }

    public function show()
    {
        phpinfo();
//        DB::collection('users')->insert([
//        'name'=>'wjb',
//        'age'=>22
//        ]);
//        $res = DB::collection('users')->get();//查询所有数据    
//
//        dd($res);

    }
//        DB::collection('users')               //选择使用users集合
//        ->insert([                          //插入数据
//            'name'  =>  'tom',
//            'age'     =>   18
//        ]);
//        $res = DB::collection('User_Former')->all();  //查询所有数据
//        dd($res);                                            //打印数据
//    }
}
