<?php

namespace App\Http\Controllers;

use App\FarmerFarm;
use App\FormerInfo;
use Illuminate\Http\Request;
use App\SensorInfo;
use App\Weights;
use App\FormerConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Session;


class FormerConfigController extends Controller
{
    protected $info;

//  個別農場感測器PAGE

    public function __construct(apiController $api)
    {
        $this->info = $api;
    }

//  更新感測器臨界值與權重
    public function updateWeightsThreshold(Request $req)
    {

        $sqlID = FormerInfo::where(['farm' => $req['farm_id'], 'farmland' => $req['farmland']])->first()['id'];

        $farm = FarmerFarm::where(['id' => $req['farm_id']])->first()['farm'];
//      內容
        $content = ['name', 'weights', 'max', 'min'];
//      感測器排列 偶數為"AI1" 基數為名稱
        $sensorOrder = explode(",", ($req['order']));
//      計算幾個個數
        $sensorCount = count($sensorOrder);
        $sensorName = [];
        $sensorInfo = [];
//      以sensor.name =['name'=>'AI1','weights'=>'10','max'=>200,'min'=>10]的模式進行切割
        for ($j = 0; $j < $sensorCount; $j += 2) {
            array_push($sensorName, $sensorOrder[$j + 1]);
            $sensorInfo[$sensorOrder[$j + 1]] = [
                //name
                $content[0] => $sensorOrder[$j],
                //weights
                $content[1] => $req->input($content[1] . '_' . $sensorOrder[$j + 1]),
                //max
                $content[2] => $req->input($content[2] . '_' . $sensorOrder[$j + 1]),
                //min
                $content[3] => $req->input($content[3] . '_' . $sensorOrder[$j + 1]),
            ];
        }
//
        foreach ($sensorName as $item) {
//          update with weights
            Weights::where(['farm_crop_id' => $sqlID, 'sensor' => $item])->update(['weights' => $sensorInfo[$item]['weights']]);
//          update with max & min
            SensorInfo::where(['former' => $req['name'], 'farm' => $req['farm_id'], 'farmland' => $req['farmland'], 'sensor' => $sensorInfo[$item]['name']])->update(['max' => $sensorInfo[$item]['max'], 'min' => $sensorInfo[$item]['min']]);
        }

        return redirect()->to(route('monitor_former_config', ['farm' => $farm, 'farmland' => $req['farmland']]));
    }

// 農夫設定監控值 新增
    public function create(Request $req)
    {
        FormerConfig::create([
            'former' => $req['former'],
            'farm' => $req['farm'],
            'farmland' => $req['farmland'],
            'sensor' => $req['sensor'],
            'switch' => $req['switch'],
            'value' => $req['value']
        ]);
        return response()->json('create_ok');
    }

// 農夫設定監控值 刪除
    public function delete(Request $req)
    {
        FormerConfig::where([
            'former' => $req['former'],
            'farm' => $req['farm'],
            'farmland' => $req['farmland'],
            'sensor' => $req['sensor']
        ])->delete();

        return response()->json('delete_ok');
    }

// 農夫設定監控值 更新
    public function update(Request $req)
    {
        FormerConfig::where([
            'former' => $req['former'],
            'farm' => $req['farm'],
            'farmland' => $req['farmland'],
            'sensor' => $req['sensor']
        ])->update([
            'sensor' => $req['sensor'],
            'switch' => $req['switch'],
            'value' => $req['value'],
        ]);
        return response()->json('update_ok');
    }

// 農夫設定監控值 改變開關
    public function switch(Request $req)
    {
//        $req = ['former' => '0000', 'farmland' => 0, 'sensor' => 'WA1', 'switch' => true, 'value' => 60];

        FormerConfig::where([
            'former' => $req['former'],
            'farm' => $req['farm'],
            'farmland' => $req['farmland'],
            'sensor' => $req['sensor']
        ])->update([
            'switch' => $req['switch'],
        ]);
        return response()->json('switch_ok');
    }

//  抓取感測器臨界值
    public function sensorChangeData($former, $farm, $farmland)
    {
        $sensorInfo = SensorInfo::Where(['former' => $former, 'farm' => $farm, 'farmland' => $farmland])->get();
        $data = [];
        foreach ($sensorInfo as $d) {
            $data[$d['sensor']] = ['max' => $d['max'], 'min' => $d['min']];
        }

        return $data;
    }

//  回傳該農夫的設定值
    public function show($farm, $farmland)
    {
        if (is_null(Auth::user())) return redirect()->to(route('former_homepage'));

        $farmer = Auth::user()['username'];

        $farmNumber = FarmerFarm::where(['farm' => $farm, 'farmer' => $farmer])->first();

        $request = new Request();
        $request->replace(['name' => $farmer, 'farm' => $farm, 'farmland' => $farmland, 'gateWay' => true]);

        $totalInfo = $this->info->numberTarget($request);

//      抓取 權重與 總和數值
        $monitorItem = [
            'weights' => $totalInfo->original['weights'],
            'target' => $totalInfo->original['target'],
        ];
//      抓取 該農夫 該農場 設定數值
        $farmerConfig = FormerConfig::where(['former' => $farmer, 'farm' => $farmNumber['id'], 'farmland' => $farmland])->get();
        if (!$farmerConfig->isEmpty()) {
            foreach ($farmerConfig as $d) {
                $d['control'] = true;
            }
        }


        $sensorChangeData = $this->sensorChangeData($farmer, $farmNumber['id'], $farmland);
//        dd($farmNumber['id']);
        return view('Form_Show.moniter.moniter_config', ['data' => $monitorItem, 'farmland' => $farmland, 'name' => $farmer, 'formerConfig' => $farmerConfig, 'sensor' => $sensorChangeData, 'farmNumber' => $farmNumber['id']]);
    }
}
