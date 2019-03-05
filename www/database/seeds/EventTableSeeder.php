<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Place;

class EventTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $maxPlaceId = count(Place::all());

        $start_date = '2019-03-01 00:00:00';
        $end_date = '2020-03-01 00:00:00';

        $minDate = strtotime($start_date);
        $maxDate = strtotime($end_date);

        $start_time = '00:00';
        $end_time = '23:59';

        $minTime = strtotime($start_time);
        $maxTime = strtotime($end_time);
        
        for($i = 0; $i < 200; $i++) {
            DB::table('event')->insert([
                'name' => Str::random(10),
                'place_id' => mt_rand(1, $maxPlaceId),
                'description' => Str::random(30),
                'starts_at' => date("Y-m-d H:i:s", mt_rand($minDate, $maxDate)),
                'duration' => date("H:i:s", mt_rand($minTime, $maxTime)),
                'active' => true,
            ]);
        }
    }
}
