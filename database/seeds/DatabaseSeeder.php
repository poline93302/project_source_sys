<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class)
        $this->call(LightReyValueSeeder::class);
        $this->call(WaterReyValueSeeder::class);
        $this->call(AirReyValueSeeder::class);
        $this->call(WeatherReyValueSeeder::class);
    }
}
