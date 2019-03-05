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
        for($i = 0; $i < 3; $i++) {
            $this->call([
                PlaceTableSeeder::class,
                EventTableSeeder::class,
                CommentTableSeeder::class,
            ]);
        }
    }
}
