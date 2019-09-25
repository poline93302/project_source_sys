<?php

namespace App\Http\Controllers;

use App\Weights;

use App\WaterRecy;
use App\WeatherRecy;
use App\LightRecy;
use App\AirRecy;
use App\SensorInfo;

use Illuminate\Http\Request;

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
            'air' => $this->confirmationPercentage($req['name'], $req['farmland'], 'AI1_001', $weights['air_hun'], $AirData['AI1_001'])
                + $this->confirmationPercentage($req['name'], $req['farmland'], 'AI2_001', $weights['air_cp'], $AirData['AI2_001'])
                + $this->confirmationPercentage($req['name'], $req['farmland'], 'AI3_001', $weights['air_ph4'], $AirData['AI3_001'])
                + $this->confirmationPercentage($req['name'], $req['farmland'], 'AI4_001', $weights['air_tem'], $AirData['AI4_001']),
            'water' => $this->confirmationPercentage($req['name'], $req['farmland'], 'WA1_001', $weights['water_level'], $WaterData["WA1_001"])
                + $this->confirmationPercentage($req['name'], $req['farmland'], 'WA2_001', $weights['water_ph'], $WaterData['WA2_001'])
                + $this->confirmationPercentage($req['name'], $req['farmland'], 'WA3_001', $weights['water_soil'], $WaterData['WA3_001']),
            'light' => $this->confirmationPercentage($req['name'], $req['farmland'], 'LIG_001', $weights['light_lux'], $LightData["LIG_001"]),
            'weather' => $this->confirmationPercentage($req['name'], $req['farmland'], 'WE1_001', $weights['weather_rainAccumulation'], $WeatherData['WE1_001'])
                + $this->confirmationPercentage($req['name'], $req['farmland'], 'WE2_001', $weights['weather_windSpeed'], $WeatherData['WE2_001'])
                + $this->confirmationPercentage($req['name'], $req['farmland'], 'WE3_001', $weights['weather_windWay'], $WeatherData['WE3_001'])
        ];
        return response()->json(['weights' => $weights, 'target' => $target]);
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
    public function airData($former, $land)
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
