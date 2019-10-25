<?php

use App\AirRecy;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AirReyValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AirRecy::truncate();
        for ($i = 0; $i <= 120; $i++) {
            AirRecy::create([
                'former' => '0000',
                'farm' => 1,
                'farmland' => 1,
                'sensor' => 'CON',
                'value' => rand(0, 100),
                'created_at' => Carbon::now()->addMinutes($i),
                'updated_at' => Carbon::now()->addMinutes($i)
            ]);
            AirRecy::create([
                'former' => '0000',
                'farm' => 1,
                'farmland' => 1,
                'sensor' => 'CHE',
                'value' => rand(0, 100),
                'created_at' => Carbon::now()->addMinutes($i),
                'updated_at' => Carbon::now()->addMinutes($i)
            ]);
            AirRecy::create([
                'former' => '0000',
                'farm' => 1,
                'farmland' => 1,
                'sensor' => 'OTE',
                'value' => rand(0, 100),
                'created_at' => Carbon::now()->addMinutes($i),
                'updated_at' => Carbon::now()->addMinutes($i)
            ]);
            AirRecy::create([
                'former' => '0000',
                'farm' => 1,
                'farmland' => 1,
                'sensor' => 'OHY',
                'value' => rand(0, 50),
                'created_at' => Carbon::now()->addMinutes($i),
                'updated_at' => Carbon::now()->addMinutes($i)
            ]);
        }
    }
}
