<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\FormerInfo;
use App\FarmerFarm;
use App\UserFormer;
use Carbon\Carbon;

class FormerPlaceController extends Controller
{
//  更新農夫名字與信箱
    public function UpdateFarmerAndEmail($user, $newName, $newEmail)
    {
        UserFormer::where(['username' => $user])->update([
            'name' => $newName,
            'email' => $newEmail
        ]);
    }

//  新增農場
    public function CreateFarm($farmID, $newFarm, $newAddress)
    {
        FarmerFarm::create([
            'id' => $farmID,
            'former' => Auth::user()['username'],
            'form' => $newFarm,
            'address' => $newAddress,
            'create_time' => Carbon::now(),
        ]);
    }

    public function CreateCrop($sqlID, $newFarm, $newCrop)
    {
        FormerInfo::create([
            'id' => $sqlID,
            'farmer' => Auth::user()['username'],
            'farm' => $newFarm,
            'status' => '444',
            'farmland' => 0,
            'crop' => $newCrop,
            'create_time' => Carbon::now(),
        ]);
    }

//  找到農場並更新
    public function findFormUpdate($farmID, $newFarm, $newAddress)
    {
//      查看是否存在
        $createRight = FarmerFarm::where([
            'farmer' => Auth::user()['username'],
            'id' => $farmID,
        ])->get();

        if ($createRight->isEmpty()) {
            $this->CreateFarm($farmID, $newFarm, $newAddress);
        } else {
            FarmerFarm::where([
                'farmer' => Auth::user()['username'],
                'farm' => $farmID,
            ])->update([
                'farm' => $newFarm,
                'address' => $newAddress,
            ]);
        }
    }

    public function findCropUpdate($sqlID, $newFarm, $newCrop)
    {
        dd($sqlID);
        $createRight = FormerInfo::where([
            'farmer' => Auth::user()['username'],
            'id' => $sqlID,
        ])->get();
        if ($createRight->isEmpty()) {
            $this->CreateCrop($sqlID, $newFarm, $newCrop);
        } else {
//            dd(FormerInfo::where(['farmer'=>Auth::user()['username'],'farm'=>$newFarm])->count());
            FormerInfo::where([
                'farmer' => Auth::user()['username'],
                'id' => $sqlID,
            ])->update([
                'farm' => $newFarm,
                'crop' => $newCrop,
                'farmland' => FormerInfo::where(['farmer' => Auth::user()['username'], 'farm' => $newFarm])->count() + 1,
            ]);
        }
    }

    public function deleteForm($farm)
    {
        FormerInfo::where([
            'former' => Auth::user()['username'],
            'form' => $farm,
        ])->delete();
    }

    public function deleteCrop($sqlID)
    {
        FormerInfo::where([
            'id' => $sqlID,
        ])->delete();
    }

    public function stepClassification(Request $req)
    {

//      temporaryDelete 1  temporaryForm 2 temporaryCrop 3
        $this->UpdateFarmerAndEmail(Auth::user()['username'], $req['updateFormerName'], $req['updateFormerEmail']);
        $checkPoint = is_null($req['temporaryDelete']) | is_null($req['temporaryForm']) | is_null($req['temporaryCrop']);

        $checkPoint ? $checkUpdate = true : $checkUpdate = false;
//        dd($req->all());
        if ($checkUpdate) {
//          農場針對傳來數值進行切割
            if (!is_null($req['temporaryForm'])) {
                $instructionForm = mb_split(',', $req['temporaryForm']);
//              切割後個別處理
                foreach ($instructionForm as $d) {
//              0 -> 原本的農場名 1->該DOM id
                    $getWay = mb_split('_', $d);
                    $this->findFormUpdate($getWay[0], $req['update-FormName-' . $getWay[1]], $req['update-FormAddress-' . $getWay[1]]);
                }
            }
//          農田針對傳來數值進行切割
            if (!is_null($req['temporaryCrop'])) {
                $instructionCrop = mb_split(',', $req['temporaryCrop']);
//                dd($instructionCrop);
//              切割後個別處理
                foreach ($instructionCrop as $d) {
//              0 -> sql id 1->DOM id
                    $getWay = mb_split('_', $d);
                    $this->findCropUpdate($getWay[0], $req['selectFormData' . $getWay[1]], $req['selectCropData' . $getWay[1]]);
                }
            }
            if (!is_null($req['temporaryDelete'])) {
                $instructionDelete = mb_split(',', $req['temporaryDelete']);
//              切割後個別處理
                foreach ($instructionDelete as $d) {
//              0 -> form or crop  1-> if form name else sqlId
                    $getWay = mb_split('_', $d);
                    if ($getWay[0] === 'form') $this->deleteForm($getWay[1]); else $this->deleteCrop($getWay[1]);
                }
            }
        }
        return redirect()->to(route('monitor_homepage'));
    }

//尋找相關的農田
    public function select(Request $req)
    {
//      將表單內容透過URL傳輸到show進行資料控制
        return redirect()->to(route('monitor_homepage', ['form' => $req['selectForm'], 'crop' => $req['selectCrop']]));
    }

//  進入show 時 的 所有資料呈現
    public function show(Request $req)
    {
        /*      input : form(def null) & former & form_crop(def null) req->form(為url抓取職) req->crop(為url抓取職)
                output : this form's monitor(water,air,sun,weather,name,crop)
        */
        if (is_null(Auth::user())) return redirect()->to(route('former_homepage'));
//      抓取當下農夫
        $former = Auth::user()['username'];
        $formerName = Auth::user()['name'];
        $formerEmail = Auth::user()['email'];
        ($req->form === 'all' || $req->form === null) ? $formQueryIndicator = 0 : $formQueryIndicator = $req->form;
        ($req->crop === 'all' || $req->crop === null) ? $cropQueryIndicator = 0 : $cropQueryIndicator = $req->crop;
//        dd($formQueryIndicator);

//      抓取該農夫的所有農場 id 排序
        $farmList = $this->selectFromShow(FarmerFarm::where('farmer', $former)->orderBy('id', 'ASC')->get());
//        dd($selectForm);
////      抓取該農夫的所有田
        $selectCrop = FormerInfo::where('farmer', $former)->orderBy('farm', 'DESC')->get();
////      提供select進行查詢用
//
        $cropsList = $this->selectCropShow(0, 0, $selectCrop);
        $resList = $this->selectCropShow($formQueryIndicator, $cropQueryIndicator, $selectCrop);
        return view('Form_Show.moniter.moniter_show', ['farmList' => $farmList, 'cropList' => $cropsList, 'resList' => $resList, 'former' => $formerName, 'formerEmail' => $formerEmail]);
    }

//  搜尋條件
    public function selectCropShow($formIndicator, $cropIndicator, $resCropList)
    {
        //     $resFormList => 農田相關資訊 $resCropList田地相關資訊
        $formList = [];
        $count = 0;
//      For Indicator computing get 2(form&crop) : 1(form) : 0(all)
        ($formIndicator || $cropIndicator) ? ($formIndicator && $cropIndicator) ? $Indicator = 2 : $Indicator = 1 : $Indicator = 0;
//      id => farmland
        if ($Indicator === 0) {
            //      進行拆解
            foreach ($resCropList as $i => $item) {
                // 組合農場與農地 #array#
                $formList[$i] = [
                    'farm' => $item['farm'],
                    'crop' => $item['crop'],
                    'status' => $item['status'],
                    'farmland' => $item['farmland'],
                    'id' => $item['id']
                ];
            }
        } elseif ($Indicator === 1) {
            foreach ($resCropList as $item) {
                // 組合農場與農地 #array#
                if ($item['farm'] === $formIndicator) {
                    $formList[$count] = [
                        'farm' => $item['farm'],
                        'crop' => $item['crop'],
                        'status' => $item['status'],
                        'farmland' => $item['farmland'],
                        'id' => $item['id']
                    ];
                    $count++;
                }
            }
        } else {
            foreach ($resCropList as $item) {
                // 組合農場與農地 #array#
                if ($item['farm'] === $formIndicator && $item['crop'] === $cropIndicator) {
                    $formList[$count] = [
                        'farm' => $item['farm'],
                        'crop' => $item['crop'],
                        'status' => $item['status'],
                        'farmland' => $item['farmland'],
                        'id' => $item['id']
                    ];
                    $count++;
                }
            }
        }
        return $formList;
    }

    public function selectFromShow($farmList)
    {
        $resList = array();

        foreach ($farmList as $farm) {
            array_push($resList, ['farm' => $farm['farm'], 'address' => $farm['address'], 'id' => $farm['id']]);
        }
        return $resList;
    }
}
