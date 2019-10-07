<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SensorInfo;
use App\Weights;
use App\FormerConfig;
use Illuminate\Support\Facades\Auth;


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
            Weights::where(['formerName' => $req['name'], 'farmland' => $req['farmland']])->update([$item => $sensorInfo[$item]['weights']]);
//          update with max & min
            SensorInfo::where(['former' => $req['name'], 'farmland' => $req['farmland'], 'sensor' => $sensorInfo[$item]['name']])->update(['max' => $sensorInfo[$item]['max'], 'min' => $sensorInfo[$item]['min']]);
        }
//        dd($req->all());

        return redirect()->to(route('monitor_former_config', ['form_crop' => $req['farmland']]));
    }

// 農夫設定監控值 新增
    public function create(Request $req)
    {
        FormerConfig::create([
            'former' => $req['former'],
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
            'farmland' => $req['farmland'],
            'sensor' => $req['sensor']
        ])->update([
            'switch' => $req['switch'],
        ]);
        return response()->json('switch_ok');
    }

//  抓取感測器臨界值
    public function sensorChangeData($former, $farmland)
    {
        $sensorInfo = SensorInfo::Where(['former' => $former, 'farmland' => $farmland])->get();
        $data = [];
        foreach ($sensorInfo as $d) {
            $data[$d['sensor']] = ['max' => $d['max'], 'min' => $d['min']];
        }

        return $data;
    }

//  回傳該農夫的設定值
    public function show($farmland)
    {
        if (is_null(Auth::user())) return redirect()->to(route('former_homepage'));

        $former = Auth::user()['username'];

        $request = new Request();
        $request->replace(['name' => $former, 'farmland' => $farmland]);

        $totalInfo = $this->info->numberTarget($request);

//      抓取 權重與 總和數值
        $monitorItem = [
            'weights' => $totalInfo->original['weights'],
            'target' => $totalInfo->original['target'],
        ];
//      抓取 該農夫 該農場 設定數值
        $formerConfig = FormerConfig::where(['former' => $former, 'farmland' => $farmland])->get();

        foreach ($formerConfig as $d) {
            $d['control'] = true;
        }

        $sensorChangeData = $this->sensorChangeData($former, $farmland);

        return view('Form_Show.moniter.moniter_config', ['data' => $monitorItem, 'farmland' => $farmland, 'name' => $former, 'formerConfig' => $formerConfig, 'sensor' => $sensorChangeData]);
    }
}
