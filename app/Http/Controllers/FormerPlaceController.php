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
        /*      input : form(def null) & former & form_crop(def null)
                output : this form's monitor(water,air,sun,weather,name,crop)
        */
//        $data = [
//            $moniter_value['water_level'] = '',
//        ];
        $Recy_data = [
            [
                'form' => 'jojogg',
                'crop' => '巧克力田',
                'water' => 83,
                'light' => 13,
                'air' => 58,
                'weather' => 95,
            ],
//            [
//                'form'    => 'jojogg',
//                'crop'    => '可田',
//                'water'   => 83,
//                'light'   => 13,
//                'air'     => 58,
//                'weather' => 90,
//            ],
//            [
//                'form'    => 'jojo32',
//                'crop'    => '利田',
//                'water'   => 83,
//                'light'   => 13,
//                'air'     => 58,
//                'weather' => 90,
//            ]
        ];
        return view('Form_Show.moniter.moniter_show', ['data' => $Recy_data]);
    }
}
