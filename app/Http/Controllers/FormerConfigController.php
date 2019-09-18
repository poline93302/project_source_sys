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

    public function show($form_crop)
    {
//      Info[0] form || Info[1] crop
        $form_crop = explode('_', $form_crop);
        $form_target = [83, 13, 58, 90];
        return view('Form_Show.moniter.moniter_config', ['data' => $form_target, 'form' => $form_crop[0], 'crop' => $form_crop[1]]);
    }
}
