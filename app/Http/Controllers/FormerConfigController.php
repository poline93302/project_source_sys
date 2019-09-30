<?php

namespace App\Http\Controllers;

use App\SensorInfo;
use Illuminate\Http\Request;
use App\FormerInfo;
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

    //農夫設定值
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

    public function delete(Request $req)
    {
        FormerConfig::where([
            'former' => $req['former'],
            'farmland' => $req['farmland'],
            'sensor' => $req['sensor']
        ])->delete();

        return response()->json('delete_ok');
    }

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

    public function sensorChangeData($former = '0000', $farmland = 0)
    {
        $sensorInfo = SensorInfo::Where(['former' => $former, 'farmland' => $farmland])->get();
        $data = [];
        foreach ($sensorInfo as $d) {
            $data[$d['sensor']] = ['max' => $d['max'], 'min' => $d['min']];
        }

        return $data;
    }

    public function show($farmland = 0)
    {
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
