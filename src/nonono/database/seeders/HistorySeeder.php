<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class HistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('history')->truncate();
        DB::table('history')->insert([
            [
                'date' => '2018/12/11',
                'discription' => '<i class="fas fa-link"></i> リンクを１件追加'
            ],
            [
                'date' => '2018/08/22',
                'discription' => '<i class="fas fa-gamepad"></i> ゲーム「<a href="/game/ushi/" target="_blank">ウシのチキンレース</a>」を公開'
            ],
            [
                'date' => '2018/06/13',
                'discription' => '<i class="fas fa-globe"></i> そざいに<strong>コモンイベント</strong>を追加'
            ],
            [
                'date' => '2018/05/09',
                'discription' => '<i class="fas fa-globe"></i> <strong>なかのひと</strong>のことや<strong>りんく</strong>を更新'
            ],
            [
                'date' => '2018/03/31',
                'discription' => '<i class="fas fa-globe"></i> そざいに<a href="/website/spt/" target="_blank">シングルページサイトテンプレート</a>を追加'
            ],
            [
                'date' => '2018/03/19',
                'discription' => '<i class="fas fa-globe"></i> Microsoft EdgeとInternet Explorerに対応'
            ],
            [
                'date' => '2018/03/06',
                'discription' => '<i class="fas fa-globe"></i> 通信量を少し削減'
            ],
            [
                'date' => '2018/03/05',
                'discription' => '<i class="fas fa-link"></i> リンクを１件追加'
            ],
            [
                'date' => '2018/03/03',
                'discription' => '<i class="fas fa-globe"></i> サイト移転'
            ],
            [
                'date' => '2018/02/23',
                'discription' => '<i class="fas fa-gamepad"></i> ゲーム「<a href="/game/chickenka/" target="_blank">集めて鶏金貨</a>」を公開'
            ],
            [
                'date' => '2017/11/12',
                'discription' => '<i class="fas fa-link"></i> リンクを１件追加<br><i class="fas fa-mobile-alt"></i> 一部のモバイル表示に対応'
            ],
            [
                'date' => '2017/09/04',
                'discription' => '<i class="fas fa-gamepad"></i> ゲーム「<a href="/game/iland/" target="_blank">アイらんど</a>」を公開'
            ],
            [
                'date' => '2017/08/31',
                'discription' => '<i class="fas fa-link"></i> リンクを１件追加'
            ],
            [
                'date' => '2017/08/12',
                'discription' => '<i class="fas fa-globe"></i> サイト公開'
            ],
        ]);
    }
}
