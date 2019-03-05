<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Event;

class CommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $maxEventId = count(Event::all());

        for($i = 0; $i < 300; $i++) {
            DB::table('comment')->insert([
                'author' => Str::random(10),
                'text' => Str::random(30),
                'event_id' => mt_rand(1, $maxEventId),
                'active' => true,
            ]);
        }
    }
}
