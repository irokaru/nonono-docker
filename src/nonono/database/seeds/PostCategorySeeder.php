<?php

use Illuminate\Database\Seeder;

class PostCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('post_categories')->truncate();
        DB::table('post_categories')->insert([
            [
                'post_id'  => 1,
                'category' => '独り言',
            ],
            [
                'post_id'  => 2,
                'category' => '創作',
            ],
            [
                'post_id'  => 3,
                'category' => 'レポート',
            ],
            [
                'post_id'  => 4,
                'category' => '独り言',
            ],
            [
                'post_id'  => 5,
                'category' => '独り言',
            ],
            [
                'post_id'  => 5,
                'category' => 'JavaScript',
            ],
            [
                'post_id'  => 6,
                'category' => 'レポート',
            ],
            [
                'post_id'  => 7,
                'category' => '独り言',
            ],
            [
                'post_id'  => 8,
                'category' => 'レポート',
            ],
            [
                'post_id'  => 9,
                'category' => 'CSS',
            ],
            [
                'post_id'  => 10,
                'category' => 'DTM',
            ],
            [
                'post_id'  => 10,
                'category' => 'レビュー',
            ],
            [
                'post_id'  => 11,
                'category' => '創作',
            ],
            [
                'post_id'  => 12,
                'category' => '独り言',
            ],
            [
                'post_id'  => 12,
                'category' => 'PHP',
            ],
            [
                'post_id'  => 13,
                'category' => '創作',
            ],
            [
                'post_id'  => 13,
                'category' => '独り言',
            ],
            [
                'post_id'  => 14,
                'category' => 'DTM',
            ],
            [
                'post_id'  => 15,
                'category' => '山歩',
            ],
            [
                'post_id'  => 16,
                'category' => '山歩',
            ],
            [
                'post_id'  => 17,
                'category' => '山歩',
            ],
            [
                'post_id'  => 18,
                'category' => 'レビュー',
            ],
            [
                'post_id'  => 19,
                'category' => '山歩',
            ],
            [
                'post_id'  => 20,
                'category' => '独り言',
            ],
            [
                'post_id'  => 21,
                'category' => '創作',
            ],
            [
                'post_id'  => 21,
                'category' => 'ゲーム制作',
            ],
            [
                'post_id'  => 22,
                'category' => '独り言',
            ],
            [
                'post_id'  => 22,
                'category' => 'ゲーム制作',
            ],
            [
                'post_id'  => 23,
                'category' => '独り言',
            ],
            [
                'post_id'  => 23,
                'category' => 'JavaScript',
            ],
            [
                'post_id'  => 23,
                'category' => 'CSS',
            ],
            [
                'post_id'  => 24,
                'category' => '独り言',
            ],
            [
                'post_id'  => 25,
                'category' => '創作',
            ],
            [
                'post_id'  => 25,
                'category' => 'ゲーム制作',
            ],
            [
                'post_id'  => 26,
                'category' => 'DTM',
            ],
            [
                'post_id'  => 27,
                'category' => '創作',
            ],
            [
                'post_id'  => 27,
                'category' => 'レビュー',
            ],
            [
                'post_id'  => 28,
                'category' => 'レポート',
            ],
            [
                'post_id'  => 29,
                'category' => '独り言',
            ],
            [
                'post_id'  => 29,
                'category' => '創作',
            ],
            [
                'post_id'  => 29,
                'category' => 'ゲーム制作',
            ],
            [
                'post_id'  => 30,
                'category' => '電子工作',
            ],
            [
                'post_id'  => 31,
                'category' => '電子工作',
            ],
            [
                'post_id'  => 32,
                'category' => 'レビュー',
            ],
            [
                'post_id'  => 33,
                'category' => '山歩',
            ],
            [
                'post_id'  => 34,
                'category' => '独り言',
            ],
            [
                'post_id'  => 35,
                'category' => 'レビュー',
            ],
            [
                'post_id'  => 36,
                'category' => 'レビュー',
            ],
            [
                'post_id'  => 37,
                'category' => '独り言',
            ],
            [
                'post_id'  => 38,
                'category' => '山歩',
            ],
            [
                'post_id'  => 39,
                'category' => '独り言',
            ],
            [
                'post_id'  => 39,
                'category' => 'レビュー',
            ],
            [
                'post_id'  => 40,
                'category' => '独り言',
            ],
            [
                'post_id'  => 41,
                'category' => '山歩',
            ],
            [
                'post_id'  => 42,
                'category' => 'PHP',
            ],
            [
                'post_id'  => 43,
                'category' => '山歩',
            ],
            [
                'post_id'  => 44,
                'category' => '独り言',
            ],
        ]);
    }
}
