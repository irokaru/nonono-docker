<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('games')->truncate();
        DB::table('games')->insert([
            [
                'title'          => 'アイらんど',
                'release_date'   => '2017/09/04',
                'release_flag'   => true,
                'thumbnail_path' => '/img/game/iland.png',
                'category'       => 'レトロチックたんさくADV',
                'infomation'    => '',
                'url'            => '/game/iland/',
            ],
            [
                'title'          => '集めて鶏金貨',
                'release_date'   => '2018/02/23',
                'release_flag'   => true,
                'thumbnail_path' => '/img/game/chickenka.png',
                'category'       => 'お手軽3Dドライブ',
                'infomation'    => '<a href=\'http://hinezumi.net/wodifes/\' target=\'_blank\'>ウディフェス外伝</a> 応募作品',
                'url'            => '/game/chickenka/',
            ],
            [
                'title'          => 'ウシのチキンレース',
                'release_date'   => '2018/08/22',
                'release_flag'   => true,
                'thumbnail_path' => '/img/game/ushi.png',
                'category'       => 'ウシ',
                'infomation'    => '<a href=\'http://www.silversecond.com/WolfRPGEditor/Contest/result10.shtml#9\' target=\'_blank\'>第10回ウディコン</a> 応募作品',
                'url'            => '/game/ushi/',
            ],
        ]);
    }
}
