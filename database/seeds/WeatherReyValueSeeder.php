<?php

use App\WeatherRecy;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class WeatherReyValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WeatherRecy::truncate();
        for ($i = 0; $i < 121; $i++) {
            WeatherRecy::create([
                'former' => '0000',
                'farm' => 1,
                'farmland' => 1,
                'sensor' => 'OWN',
                'value' => rand(0, 360),
                'created_at' => Carbon::now()->subMinutes($i*5),
                'updated_at' => Carbon::now()->subMinutes($i*5),
            ]);
            WeatherRecy::create([
                'former' => '0000',
                'farm' => 1,
                'farmland' => 1,
                'sensor' => 'OWS',
                'value' => rand(0, 10),
                'created_at' => Carbon::now()->subMinutes($i*5),
                'updated_at' => Carbon::now()->subMinutes($i*5),
            ]);
            WeatherRecy::create([
                'former' => '0000',
                'farm' => 1,
                'farmland' => 1,
                'sensor' => 'ORA',
                'value' => rand(0, 200),
                'created_at' => Carbon::now()->subMinutes($i*5),
                'updated_at' => Carbon::now()->subMinutes($i*5),
            ]);
        }
    }
}
