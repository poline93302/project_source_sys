<?php

namespace App\Http\Controllers;

use App\FarmerFarm;
use App\Weights;

use App\WaterRecy;
use App\WeatherRecy;
use App\LightRecy;
use App\AirRecy;
use App\SensorInfo;

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
                + $this->confirmationPercentage($req['name'], $farmID['id'], $req['farmland'], 'WPH', $weights['water_ph'], $WaterData['WPH'])
                + $this->confirmationPercentage($req['name'], $farmID['id'], $req['farmland'], 'WSO', $weights['water_soil'], $WaterData['WSO'])),
            'light' => round($this->confirmationPercentage($req['name'], $farmID['id'], $req['farmland'], 'LFS', $weights['light_lux'], $LightData["LFS"])),
            'weather' => round($this->confirmationPercentage($req['name'], $farmID['id'], $req['farmland'], 'ORA', $weights['weather_rainAccumulation'], $WeatherData['ORA'])
                + $this->confirmationPercentage($req['name'], $farmID['id'], $req['farmland'], 'OWS', $weights['weather_windSpeed'], $WeatherData['OWS'])
                + $this->confirmationPercentage($req['name'], $farmID['id'], $req['farmland'], 'OWN', $weights['weather_windWay'], $WeatherData['OWN']))
        ];
        if ($req['gateWay'])
            $target['environment'] =
                round($this->confirmationPercentage($req['name'], $farmID['id'], $req['farmland'], 'OHY', $weights['air_tem'], $AirData['OHY'])
                    + $this->confirmationPercentage($req['name'], $farmID['id'], $req['farmland'], 'OTE', $weights['air_hun'], $AirData['OTE']));
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
                    $sensorTotal = ['OTE', 'OHY'];
                    break;
                }
            case('air'):
                {
                    $sensorTotal = ['CON', 'CHE'];
                    break;
                }
            case('water'):
                {
                    $sensorTotal = ['WLS', 'WPH', 'WSO'];
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
        $sensors = ['WPH', 'WSO', 'WLS'];
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
            'all' => ['OTE', 'OHY', 'CHE', 'CON'],
            'environment' => ['OTE', 'OHY'],
            'air' => ['CHE', 'CON']
        ];
        $data = array();

        foreach ($sensors as $k => $sensor) {
            if ($type === $k) {
                foreach ($sensor as $item) {
                    $Air = AirRecy::where(['former' => $former, 'farm' => $farm, 'farmland' => $land, 'sensor' => $item])->orderBy('created_at', 'DESC')->first();;
                    $data[$Air['sensor']] = $Air['value'];
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


    public function sensorHistoryBy($sensor, $former, $farm, $farmland, $type)
    {
        return response()->json('OYYYYY');
    }
}
