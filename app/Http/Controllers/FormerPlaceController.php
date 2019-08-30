<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormerPlaceController extends Controller
{
    //農夫新增農場
    public function create()
    {

    }

    public function delete()
    {

    }

    public function update()
    {

    }

    public function show(Request $req)
    {
        /*      input : form & former & form_crop
                output : this form's monitor(water,air,sun,weather)
        */
        $data = [
            $moniter_value['water_level'] = '',
        ];
        return view('Form_Show.moniter.moniter_show', $data);
    }
}
