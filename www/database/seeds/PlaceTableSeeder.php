<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlaceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i < 100; $i++) {
            DB::table('place')->insert([
                'name' => Str::random(10),
                'longitude' => mt_rand(-180, 180),
                'latitude' => mt_rand(-85, 85),
                'active' => true,
            ]);
        }
    }
}
