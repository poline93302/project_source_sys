<?php

namespace App\Http\Controllers;

use App\Weights;

use App\WaterRecy;
use App\WeatherRecy;
use App\LightRecy;
use App\AirRecy;
use App\SensorInfo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class apiController extends Controller
{
//  抓取大數據
    public function numberTarget(Request $req)
    {
        $weights = $this->weightsData($req['name'], $req['farmland']);

        $AirData = $this->airData($req['name'], $req['farmland']);
        $WaterData = $this->waterData($req['name'], $req['farmland']);
        $LightData = $this->lightData($req['name'], $req['farmland']);
        $WeatherData = $this->weatherData($req['name'], $req['farmland']);

        $target = [
            'air' => round($this->confirmationPercentage($req['name'], $req['farmland'], 'AI1', $weights['air_hun'], $AirData['AI1'])
                + $this->confirmationPercentage($req['name'], $req['farmland'], 'AI2', $weights['air_cp'], $AirData['AI2'])
                + $this->confirmationPercentage($req['name'], $req['farmland'], 'AI3', $weights['air_ph4'], $AirData['AI3'])
                + $this->confirmationPercentage($req['name'], $req['farmland'], 'AI4', $weights['air_tem'], $AirData['AI4'])),
            'water' => round($this->confirmationPercentage($req['name'], $req['farmland'], 'WA1', $weights['water_level'], $WaterData["WA1"])
                + $this->confirmationPercentage($req['name'], $req['farmland'], 'WA2', $weights['water_ph'], $WaterData['WA2'])
                + $this->confirmationPercentage($req['name'], $req['farmland'], 'WA3', $weights['water_soil'], $WaterData['WA3'])),
            'light' => round($this->confirmationPercentage($req['name'], $req['farmland'], 'LIG', $weights['light_lux'], $LightData["LIG"])),
            'weather' => round($this->confirmationPercentage($req['name'], $req['farmland'], 'WE1', $weights['weather_rainAccumulation'], $WeatherData['WE1'])
                + $this->confirmationPercentage($req['name'], $req['farmland'], 'WE2', $weights['weather_windSpeed'], $WeatherData['WE2'])
                + $this->confirmationPercentage($req['name'], $req['farmland'], 'WE3', $weights['weather_windWay'], $WeatherData['WE3']))
        ];
        return response()->json(['weights' => $weights, 'target' => $target]);
    }

//  抓取最新資料
    public function getNowData(Request $req)
    {
//        $req = [ 'name'=>'0000','farmland'=>0,'type'=>'air'];
        $sensorInfo = [];
        $value = [];
        $time = 0;

        if ($req['type'] === 'air') $value = $this->airData($req['name'], $req['farmland']);
        if ($req['type'] === 'light') $value = $this->lightData($req['name'], $req['farmland']);
        if ($req['type'] === 'water') $value = $this->waterData($req['name'], $req['farmland']);
        if ($req['type'] === 'weather') $value = $this->weatherData($req['name'], $req['farmland']);

        $MaxAndMin = $this->sensorDataInterval($req['name'], $req['farmland'], $req['type']);

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
    public function weightsData($former, $land)
    {
        $weights = Weights::where(['formerName' => $former, 'farmland' => $land])->first();
        return ($weights);
    }

//  抓取感測器的MAX MIN
    public function sensorData($former, $farmland)
    {
        $sensorInfo = SensorInfo::Where(['former' => $former, 'farmland' => $farmland])->get();
        return $sensorInfo;
    }

// 抓取個別的MAX MIN
    public function sensorDataInterval($former, $farmland, $type)
    {
        $sensorInfo = [];
        $sensorTotal = [];
        switch ($type) {
            case('air'):
                {
                    $sensorTotal = ['AI1', 'AI2', 'AI3', 'AI4'];
                    break;
                }
            case('water'):
                {
                    $sensorTotal = ['WA1', 'WA2', 'WA3'];
                    break;
                }
            case('light'):
                {
                    $sensorTotal = ['LIG'];
                    break;
                }
            case('weather'):
                {
                    $sensorTotal = ['WE1', 'WE2', 'WE3'];
                    break;
                }
        }
        foreach ($sensorTotal as $item) {
            $info = SensorInfo::Where(['former' => $former, 'farmland' => $farmland, 'sensor' => $item])->first();
            $sensorInfo[$info['sensor']] = [
                'max' => $info['max'],
                'min' => $info['min'],
            ];
        }
        return $sensorInfo;
    }

//  最新一筆的感測器資料抓出
    public function waterData($former, $land)
    {
        $data = array();
        $Water = WaterRecy::where(['former' => $former, 'farmland' => $land])->orderBy('send_time', 'ASC')->get();

        foreach ($Water as $Data) {
            $data[$Data['sensor']] = $Data['value'];
        }
        return $data;
    }

//  最新一筆的感測器資料抓出
    public function airData($former = '0000', $land = 0)
    {
        $data = array();
        $Air = AirRecy::where(['former' => $former, 'farmland' => $land])->orderBy('send_time', 'ASC')->get();

        foreach ($Air as $Data) {
            $data[$Data['sensor']] = $Data['value'];
        }

        return $data;
    }

//  最新一筆的感測器資料抓出
    public function lightData($former, $land)
    {
        $data = [];
        $Light = LightRecy::where(['former' => $former, 'farmland' => $land])->orderBy('send_time', 'ASC')->get();

        foreach ($Light as $Data) {
            $data[$Data['sensor']] = $Data['value'];
        }

        return $data;
    }

//  最新一筆的感測器資料抓出
    public function weatherData($former, $land)
    {
        $data = array();
        $Weather = WeatherRecy::where(['former' => $former, 'farmland' => $land])->orderBy('send_time', 'ASC')->get();

        foreach ($Weather as $Data) {
            $data[$Data['sensor']] = $Data['value'];
        }

        return $data;
    }

//  計算該數值在該領域的百分比
    public function confirmationPercentage($former, $farmland, $sensor, $weights, $original)
    {
        $info = SensorInfo::Where(['former' => $former, 'farmland' => $farmland, 'sensor' => $sensor])->first();
        $range = $info['max'] - $info['min'];
        $value = $original * ($weights / $range);
        return $value;
    }

}
