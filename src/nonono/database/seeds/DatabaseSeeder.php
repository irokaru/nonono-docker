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
        $this->call(GamesSeeder::class);
        $this->call(HistorySeeder::class);
        $this->call(PostSeeder::class);
        $this->call(PostCategorySeeder::class);
    }
}
