<?php

namespace App\Http\Controllers;

use App\UserFormer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\FormerInfo;

class FormerPlaceController extends Controller
{

    public function createFormAddress($value, $form)
    {
        FormerInfo::where('form', $form)->update(['address' => $value]);
    }

    public function creatFormName($value)
    {
        FormerInfo::create([
            'former' => Auth::user()['username'],
            'form' => $value,
            'create_time' => Carbon::now(),
        ]);
    }

    public function createFormCrop($form, $crop)
    {
        $cropInfo = FormerInfo::where(['form' => $form])->first();

        FormerInfo::create([
            'former' => Auth::user()['username'],
            'form' => $form,
            'form_plant' => $crop,
            'address' => $cropInfo['address'],
            'create_time' => Carbon::now(),
        ]);
    }

    public function updateForm($value, $form)
    {
        FormerInfo::where(['form' => $form])->update([
            'form' => $value,
        ]);
    }

    public function updateCrop($sqlID, $form, $crop)
    {
        FormerInfo::where(['id' => $sqlID])->update([
            'form' => $form,
            'form_plant' => $crop,
        ]);
    }

    public function deleteForm($name)
    {
        FormerInfo::where(['form' => $name])->delete();
    }

    public function deleteCrop($id)
    {
        FormerInfo::where(['id' => $id])->delete();
    }

    public function update($moment, $form, $value)
    {
        if ($moment === 'FormAddress') {
            $this->createFormAddress($value, $form);
        } else {
            $this->updateForm($value, $form);
        }
    }

    public function stepClassification(Request $req)
    {
        $instructionInfo = array();
        $instructionForm = array();
        $instructionCrop = array();

        //      最後一定為空不理他 mb_split放進陣列
//        dd($req['temporary']);
        is_null($req['temporary']) ? $checkUpdate = false : $checkUpdate = true;

        if ($checkUpdate) {
//          針對傳來數值進行切割
            $instruction = mb_split(',', $req['temporary']);
            foreach ($instruction as $d) {
//              下方分類

                $getWay = mb_split('_', $d);

                $value = $getWay[1] . ',' . $getWay[2];
                if ($getWay[0] === 'Form') {
                    array_push($instructionForm, $value);
                } else if ($getWay[0] === 'Crop') {
                    $value = $value . ',' . $getWay[3];
                    array_push($instructionCrop, $value);
                } else if ($getWay[0] === 'delete') {
                    if ($getWay[1] === 'Form') {
                        $this->deleteForm($getWay[2]);
                    } else {
                        $this->deleteCrop($getWay[2]);
                    }
                } else {
//                  Info
                    array_push($instructionInfo, $value);
                }
            }

//          info 存在
            if (($instructionInfo !== [])) {
//          更改名字
                foreach ($instructionInfo as $info) {
                    $infoComeForm = mb_split(',', $info)[1];
                    $infoComeForm === 'updateFormerName' ? $team = 'name' : $team = 'email';
                    UserFormer::where('username', Auth::user()['username'])->update([$team => $req[$infoComeForm]]);
                }
            }
//          form 存在
            if ($instructionForm !== []) {
                foreach ($instructionForm as $Form) {
                    $instructionVariable = mb_split(',', $Form);
                    $instructionComeForm = mb_split('-', $instructionVariable[1]);
                    if ($instructionVariable[0] == 'undefined') {
//                  新增農場
                        $this->creatFormName($req[$instructionVariable[1]]);
                    } else {
//                  修改農場地址（包括新增時）
                        $this->update($instructionComeForm[1], $instructionVariable[0], $req[$instructionVariable[1]]);
                    }
                }
            }
//          Crop
            if ($instructionCrop !== []) {
                foreach ($instructionCrop as $Crop) {
                    $cropComeFrom = mb_split(',', $Crop);
                    $reqForm = 'selectFormData' . $cropComeFrom[1];
                    $reqCrop = 'selectCropData' . $cropComeFrom[1];
                    if ($cropComeFrom[2] === 'undefined') {
                        $this->createFormCrop($req[$reqForm], $req[$reqCrop]);
                    } else {
                        $this->updateCrop($cropComeFrom[2], $req[$reqForm], $req[$reqCrop]);
                    }
                }
            }

        }
        return redirect()->to(route('monitor_homepage'));
    }

    public function select(Request $req)
    {
//      將表單內容透過URL傳輸到show進行資料控制
        return redirect()->to(route('monitor_homepage', ['form' => $req['selectForm'], 'crop' => $req['selectCrop']]));
    }

    public function show(Request $req)
    {
        /*      input : form(def null) & former & form_crop(def null) req->form(為url抓取職) req->crop(為url抓取職)
                output : this form's monitor(water,air,sun,weather,name,crop)
        */

//      抓取當下農夫
        $former = Auth::user()['username'];
        $formerName = Auth::user()['name'];
        $formerEmail = Auth::user()['email'];
        ($req->form === 'all' || $req->form === null) ? $formQueryIndicator = 0 : $formQueryIndicator = $req->form;
        ($req->crop === 'all' || $req->crop === null) ? $cropQueryIndicator = 0 : $cropQueryIndicator = $req->crop;

//      抓取該農夫的所有田
        $selectFormer = FormerInfo::where('former', $former)->orderBy('form', 'DESC')->get();

//      提供select進行查詢用
        $formList = $this->selectShow(0, 0, $selectFormer);
        $resList = $this->selectShow($formQueryIndicator, $cropQueryIndicator, $selectFormer);

        return view('Form_Show.moniter.moniter_show', ['resList' => $resList, 'formList' => $formList, 'former' => $formerName, 'formerEmail' => $formerEmail]);
    }

    public function selectShow($formIndicator, $cropIndicator, $resList)
    {
        //      [0]=>form_crop,[1]=>form_Address
        $formList = [];
        $count = 0;
//      For Indicator computing get 2(form&crop) : 1(form) : 0(all)
        ($formIndicator || $cropIndicator) ? ($formIndicator && $cropIndicator) ? $Indicator = 2 : $Indicator = 1 : $Indicator = 0;

        if ($Indicator === 0) {
            //      進行拆解
            foreach ($resList as $i => $item) {
                // 組合農場與農地 #array#
                $formList[$i][0] = $item['form'] . '_' . $item['form_plant'];
                $formList[$i][1] = $item['address'];
                $formList[$i][2] = $item['id'];
            }
        } elseif ($Indicator === 1) {
            foreach ($resList as $item) {
                // 組合農場與農地 #array#
                if ($item['form'] === $formIndicator) {
                    $formList[$count][0] = $item['form'] . '_' . $item['form_plant'];
                    $formList[$count][1] = $item['address'];
                    $formList[$count][2] = $item['id'];
                    $count++;
                }
            }
        } else {
            foreach ($resList as $item) {
                // 組合農場與農地 #array#
                if ($item['form'] === $formIndicator && $item['form_plant'] === $cropIndicator) {
                    $formList[$count][0] = $item['form'] . '_' . $item['form_plant'];
                    $formList[$count][1] = $item['address'];
                    $formList[$count][2] = $item['id'];
                    $count++;
                }
            }
        }

        return $formList;
    }
}
