<?php

namespace App\Http\Controllers;

use App\FarmerFarm;
use App\Weights;

use App\WaterRecy;
use App\WeatherRecy;
use App\LightRecy;
use App\AirRecy;
use App\SensorInfo;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class apiController extends Controller
{
//  抓取大數據
    public function numberTarget(Request $req)
    {

        $farmID = FarmerFarm::where(['farmer' => $req['name'], 'farm' => $req['farm']])->first();

        $weights = $this->weightsData($req['name'], $farmID['id'], $req['farmland']);

        $AirData = $this->airData($req['name'], $farmID['id'], $req['farmland'], 'all');
        $WaterData = $this->waterData($req['name'], $farmID['id'], $req['farmland']);
        $LightData = $this->lightData($req['name'], $farmID['id'], $req['farmland']);
        $WeatherData = $this->weatherData($req['name'], $farmID['id'], $req['farmland']);
//
        $target = [
            'air' => round($this->confirmationPercentage($req['name'], $farmID['id'], $req['farmland'], 'CON', $weights['air_cp'], $AirData['CON'])
                + $this->confirmationPercentage($req['name'], $farmID['id'], $req['farmland'], 'CHE', $weights['air_ph4'], $AirData['CHE'])),

            'water' => round($this->confirmationPercentage($req['name'], $farmID['id'], $req['farmland'], 'WLS', $weights['water_level'], $WaterData["WLS"])
                + $this->confirmationPercentage($req['name'], $farmID['id'], $req['farmland'], 'WPH', $weights['water_ph'], $WaterData['WPH'])),

            'light' => round($this->confirmationPercentage($req['name'], $farmID['id'], $req['farmland'], 'LFS', $weights['light_lux'], $LightData["LFS"])),

            'weather' => round($this->confirmationPercentage($req['name'], $farmID['id'], $req['farmland'], 'ORA', $weights['weather_rainAccumulation'], $WeatherData['ORA'])
                + $this->confirmationPercentage($req['name'], $farmID['id'], $req['farmland'], 'OWS', $weights['weather_windSpeed'], $WeatherData['OWS'])
                + $this->confirmationPercentage($req['name'], $farmID['id'], $req['farmland'], 'OWN', $weights['weather_windWay'], $WeatherData['OWN']))
        ];
        if ($req['gateWay'])
            $target['environment'] =
                round($this->confirmationPercentage($req['name'], $farmID['id'], $req['farmland'], 'OHY', $weights['air_tem'], $AirData['OHY'])
                    + $this->confirmationPercentage($req['name'], $farmID['id'], $req['farmland'], 'OTE', $weights['air_hun'], $AirData['OTE'])
                    + $this->confirmationPercentage($req['name'], $farmID['id'], $req['farmland'], 'WSO', $weights['water_soil'], $AirData['WSO']));
        return response()->json(['weights' => $weights, 'target' => $target]);
    }

//  抓取最新資料
    public function getNowData(Request $req)
    {
//        $req = ['name' => '0000', 'farm' => 1, 'farmland' => 1, 'type' => 'light'];
        $sensorInfo = [];
        $value = [];
        $time = 0;

        if ($req['type'] === 'environment') $value = $this->airData($req['name'], $req['farm'], $req['farmland'], 'environment');
        if ($req['type'] === 'air') $value = $this->airData($req['name'], $req['farm'], $req['farmland'], 'air');
        if ($req['type'] === 'light') $value = $this->lightData($req['name'], $req['farm'], $req['farmland']);
        if ($req['type'] === 'water') $value = $this->waterData($req['name'], $req['farm'], $req['farmland']);
        if ($req['type'] === 'weather') $value = $this->weatherData($req['name'], $req['farm'], $req['farmland']);


        $MaxAndMin = $this->sensorDataInterval($req['name'], $req['farm'], $req['farmland'], $req['type']);

        foreach ($MaxAndMin as $k => $item) {
            $sensorInfo[$time] = [
                'sensor' => $k,
                'value' => $value[$k],
                'max' => $item['max'],
                'min' => $item['min']
            ];
            $time++;
        }

        return response()->json($sensorInfo);
    }

//  抓取權重
    public function weightsData($former, $farm, $land)
    {
//        dd($former);

        $weightsInfo = DB::table('weights')
            ->join('formerInfo', function ($join) {
                $join->on('weights.farm_crop_id', '=', 'formerInfo.id');
            })
            ->where(['formerInfo.farmer' => $former, 'formerInfo.farm' => $farm, 'formerInfo.farmland' => $land])
            ->get();

        foreach ($weightsInfo as $item) {
            $weights[$item->sensor] = $item->weights;
        }
//        $weights = Weights::where(['formerName' => $former, 'farm' => $farm, 'farmland' => $land])->get();
        return ($weights);
    }

//  抓取感測器的MAX MIN
    public function sensorData($former, $farmland)
    {
        $sensorInfo = SensorInfo::Where(['former' => $former, 'farmland' => $farmland])->get();
        return $sensorInfo;
    }

// 抓取個別的MAX MIN
    public function sensorDataInterval($former, $farm, $farmland, $type)
    {
        $sensorInfo = [];
        $sensorTotal = [];
        switch ($type) {
            case('environment'):
                {
                    $sensorTotal = ['OTE', 'OHY', 'WSO'];
                    break;
                }
            case('air'):
                {
                    $sensorTotal = ['CON', 'CHE'];
                    break;
                }
            case('water'):
                {
                    $sensorTotal = ['WLS', 'WPH'];
                    break;
                }
            case('light'):
                {
                    $sensorTotal = ['LFS'];
                    break;
                }
            case('weather'):
                {
                    $sensorTotal = ['OWN', 'OWS', 'ORA'];
                    break;
                }
        }
        foreach ($sensorTotal as $item) {
            $info = SensorInfo::Where(['former' => $former, 'farm' => $farm, 'farmland' => $farmland, 'sensor' => $item])->first();
            $sensorInfo[$info['sensor']] = [
                'max' => $info['max'],
                'min' => $info['min'],
            ];
        }

        return $sensorInfo;
    }

//  最新一筆的感測器資料抓出
    public function waterData($former, $farm, $land)
    {
        $sensors = ['WPH', 'WLS'];
        $data = array();
        foreach ($sensors as $sensor) {
            $Water = WaterRecy::where(['former' => $former, 'farm' => $farm, 'farmland' => $land, 'sensor' => $sensor])->orderBy('created_at', 'DESC')->first();//created_at
            $data[$Water['sensor']] = $Water['value'];
        }
        return $data;
    }

//  最新一筆的感測器資料抓出
    public function airData($former, $farm, $land, $type)
    {
        $sensors = [
            'all' => ['OTE', 'OHY', 'CHE', 'CON','WSO'],
            'environment' => ['OTE', 'OHY','WSO'],
            'air' => ['CHE', 'CON']
        ];
        $data = array();

        foreach ($sensors as $k => $sensor) {
            if ($type === $k) {
                foreach ($sensor as $item) {
                    if($item === 'WSO'){
                        $upData = WaterRecy::where(['former' => $former, 'farm' => $farm, 'farmland' => $land, 'sensor' => $item])->orderBy('created_at', 'DESC')->first();;
                    }else
                        $upData = AirRecy::where(['former' => $former, 'farm' => $farm, 'farmland' => $land, 'sensor' => $item])->orderBy('created_at', 'DESC')->first();

                    $data[$upData['sensor']] = $upData['value'];
                }
            }
        }
        return $data;
    }

//  最新一筆的感測器資料抓出
    public function lightData($former, $farm, $land)
    {
        $sensors = ['LFS'];
        $data = [];
        foreach ($sensors as $sensor) {
            $Light = LightRecy::where(['former' => $former, 'farm' => $farm, 'farmland' => $land, 'sensor' => $sensor])->orderBy('created_at', 'DESC')->first();//created_at
            $data[$Light['sensor']] = $Light['value'];
        }


        return $data;
    }

//  最新一筆的感測器資料抓出
    public function weatherData($former = 0000, $farm = 1, $land = 1)
    {
        $sensors = ['OWN', 'OWS', 'ORA'];
        $data = array();


        foreach ($sensors as $sensor) {
            $Weather = WeatherRecy::where(['former' => $former, 'farm' => $farm, 'farmland' => $land, 'sensor' => $sensor])->orderBy('created_at', 'DESC')->first();//created_at
            $data[$Weather['sensor']] = $Weather['value'];
        }
        return $data;
    }

//  計算該數值在該領域的百分比
    public function confirmationPercentage($former, $farm, $farmland, $sensor, $weights, $original)
    {
        $info = SensorInfo::where(['former' => $former, 'farm' => $farm, 'farmland' => $farmland, 'sensor' => $sensor])->first();
        $range = $info['max'] - $info['min'];
        $value = $original * ($weights / $range);
        return $value;
    }

//  抓取歷史資料
    public function sensorHistoryBy(Request $req)
    {
//      farm farmland sensor farmer type
//        $req = ['name' => '0000','farmer'=>'0000', 'farm' => 1, 'farmland' => 1, 'type' => 'water','sensor'=>'water_level'];
        $result = [];
//      先切割後 選擇table +Recy
        $tableWord = mb_split('_', $req['sensor']);

        $table = $tableWord[0] . 'Recy';

//      對應感測器表
        $switchSensorCorr = [
            'water' => [
                'water_level' => 'WLS',
                'water_ph' => 'WPH',
            ],
            'environment' => [
                'air_hun' => 'OTE',
                'air_tem' => 'OHY',
                'water_soil' => 'WSO',
            ],
            'air' => [
                'air_cp' => 'CON',
                'air_ph4' => 'CHE',
            ],
            'weather' => [
                'weather_windWay' => 'OWN',
                'weather_windSpeed' => 'OWS',
                'weather_rainAccumulation' => 'ORA',
            ],
            'light' => [
                'light_lux' => 'LFS',
            ],
        ];
//      抓取最大值
        $sensorMaxMin = SensorInfo::where([
            'former' => $req['farmer'],
            'farm' => $req['farm'],
            'farmland' => $req['farmland'],
            'sensor' => $switchSensorCorr[$req['type']][$req['sensor']],
        ])->first();

//      抓取歷史資料
        $dbData = DB::table($table)->where([
            'former' => $req['farmer'],
            'farm' => $req['farm'],
            'farmland' => $req['farmland'],
            'sensor' => $switchSensorCorr[$req['type']][$req['sensor']],
        ])
            ->orderBy('created_at', 'DESC')
            ->take(336)
            ->get();


        foreach ($dbData as $data) {
            array_push($result, [
                'value' => $data->value,
                'time' => $data->created_at,
            ]);
        }

        $res = [
            'max' => $sensorMaxMin['max'],
            'min' => $sensorMaxMin['min'],
            'res' => $result,
        ];

        return response()->json($res);
    }
}
