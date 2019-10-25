<?php

use App\WaterRecy;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class WaterReyValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WaterRecy::truncate();

        for ($i = 0; $i < 121; $i++) {
            WaterRecy::create([
                'former' => '0000',
                'farm' => 1,
                'farmland' => 1,
                'sensor' => 'WPH',
                'value' => rand(0, 14),
                'created_at' => Carbon::now()->addMinute($i),
                'updated_at' => Carbon::now()->addMinute($i),
            ]);
            WaterRecy::create([
                'former' => '0000',
                'farm' => 1,
                'farmland' => 1,
                'sensor' => 'WLS',
                'value' => rand(0, 200),
                'created_at' => Carbon::now()->addMinute($i),
                'updated_at' => Carbon::now()->addMinute($i),
            ]);
            WaterRecy::create([
                'former' => '0000',
                'farm' => 1,
                'farmland' => 1,
                'sensor' => 'WSO',
                'value' => rand(0, 100),
                'created_at' => Carbon::now()->addMinute($i),
                'updated_at' => Carbon::now()->addMinute($i),
            ]);
        }
    }
}
