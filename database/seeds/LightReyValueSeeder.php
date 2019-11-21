<?php

use App\LightRecy;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LightReyValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LightRecy::truncate();
        for ($i = 0; $i <= 120; $i++) {
            LightRecy::create([
                'former' => '0000',
                'farm' => 1,
                'farmland' => 1,
                'sensor' => 'LFS',
                'value' => rand(200, 2000),
                'created_at' => Carbon::now()->subMinutes($i*5),
                'updated_at' => Carbon::now()->subMinutes($i*5)
            ]);
        }
    }
}
