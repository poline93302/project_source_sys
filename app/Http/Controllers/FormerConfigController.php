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
        $formInfo = explode('_', $form_crop);

        return view('Form_Show.moniter.moniter_config', $formInfo);
    }
}
