<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\FormerInfo;

class FormerPlaceController extends Controller
{

    //農夫新增農場
    public function create()
    {

    }

    public function delete()
    {

    }

    public function select(Request $req)
    {

    }

    public function show()
    {
        /*      input : form(def null) & former & form_crop(def null)
                output : this form's monitor(water,air,sun,weather,name,crop)
        */
//      抓取當下農夫
        $former = Auth::user()['name'];
//      抓取該農夫的所有田
        $selectFormer = FormerInfo::where('former', $former)->orderBy('form', 'DESC')->get();
//      form_crop
        $formList = [];
        $formAddress = [];
//      進行拆解
        foreach ($selectFormer as $i => $item) {
//          組合農場與農地 #array#
            $formList[$i] = $item['form'] . '_' . $item['form_plant'];
            $formAddress[$i] = $item['address'];
        }
        return view('Form_Show.moniter.moniter_show', ['formList' => $formList, 'former' => $former, 'formerAddress' => $formAddress]);
    }
}
