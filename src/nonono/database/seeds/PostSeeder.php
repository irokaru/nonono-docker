<?php

use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('posts')->truncate();
        DB::table('posts')->insert([
            [
                'title'        => '移転と技術と色々と',
                'date'         => '2018/03/03',
                'category'     => '独り言',
                'release_flag' => true,
            ],
            [
                'title'        => '創作に使える便利なサイト紹介',
                'date'         => '2018/03/06',
                'category'     => '創作',
                'release_flag' => true,
            ],
            [
                'title'        => '第９回うでぃおふに参加しました',
                'date'         => '2018/03/17',
                'category'     => 'レポート',
                'release_flag' => true,
            ],
            [
                'title'        => '旧サイト引き払いました',
                'date'         => '2018/04/27',
                'category'     => '独り言',
                'release_flag' => true,
            ],
            [
                'title'        => '外部ファイル管理のススメ',
                'date'         => '2018/05/12',
                'category'     => '独り言, JavaScript',
                'release_flag' => true,
            ],
            [
                'title'        => 'BitSummit Vol.6 に行ってきました',
                'date'         => '2018/05/13',
                'category'     => 'レポート',
                'release_flag' => true,
            ],
            [
                'title'        => 'Cloud9 の共同開発が楽しかった件',
                'date'         => '2018/06/06',
                'category'     => '独り言',
                'release_flag' => true,
            ],
            [
                'title'        => '同人ゲームfes 10 に行ってきました',
                'date'         => '2018/06/09',
                'category'     => 'レポート',
                'release_flag' => true,
            ],
            [
                'title'        => 'Sass を使ってみたら小さな革命が起きた件',
                'date'         => '2018/07/11',
                'category'     => 'CSS',
                'release_flag' => true,
            ],
            [
                'title'        => 'Keystation Mini 32 II を買いました',
                'date'         => '2018/07/18',
                'category'     => 'DTM, レビュー',
                'release_flag' => true,
            ],
            [
                'title'        => '実は第10回ウディコン出てます',
                'date'         => '2018/07/29',
                'category'     => '創作',
                'release_flag' => true,
            ],
            [
                'title'        => 'SQL から逃げつつ DB から逃げない',
                'date'         => '2018/08/01',
                'category'     => '独り言, PHP',
                'release_flag' => true,
            ],
            [
                'title'        => 'ウディコン15位でした＆新作作ってます',
                'date'         => '2018/08/22',
                'category'     => '創作, 独り言',
                'release_flag' => true,
            ],
            [
                'title'        => 'dexed 使いました',
                'date'         => '2018/09/08',
                'category'     => 'DTM',
                'release_flag' => true,
            ],
            [
                'title'        => '大山に登ってきました',
                'date'         => '2018/09/24',
                'category'     => '山歩',
                'release_flag' => true,
            ],
            [
                'title'        => '立山（雄山）に登ってきました',
                'date'         => '2018/10/24',
                'category'     => '山歩',
                'release_flag' => true,
            ],
            [
                'title'        => '比叡山に登ってきました',
                'date'         => '2018/11/11',
                'category'     => '山歩',
                'release_flag' => true,
            ],
            [
                'title'        => 'Deco 02 を買いました',
                'date'         => '2018/12/11',
                'category'     => 'レビュー',
                'release_flag' => true,
            ],
            [
                'title'        => '高見山に登ってきました',
                'date'         => '2018/12/31',
                'category'     => '山歩',
                'release_flag' => true,
            ],
            [
                'title'        => '2019年あけました',
                'date'         => '2019/01/01',
                'category'     => '独り言',
                'release_flag' => true,
            ],
            [
                'title'        => '友人のためにゲームを作りました',
                'date'         => '2019/01/12',
                'category'     => '創作, ゲーム制作',
                'release_flag' => true,
            ],
            [
                'title'        => 'RPG の経験値獲得について考えました',
                'date'         => '2019/01/29',
                'category'     => '独り言, ゲーム制作',
                'release_flag' => true,
            ],
            [
                'title'        => 'ウディフェス２のサイトが公開されました',
                'date'         => '2019/02/04',
                'category'     => '独り言, JavaScript, CSS',
                'release_flag' => true,
            ],
            [
                'title'        => 'ウディフェス２の始まりですよ',
                'date'         => '2019/02/14',
                'category'     => '独り言',
                'release_flag' => true,
            ],
            [
                'title'        => 'チームで新作作りました',
                'date'         => '2019/03/02',
                'category'     => '創作, ゲーム制作',
                'release_flag' => true,
            ],
            [
                'title'        => 'Cubase 買いました',
                'date'         => '2019/03/09',
                'category'     => 'DTM',
                'release_flag' => true,
            ],
            [
                'title'        => '名刺を新調しました',
                'date'         => '2019/03/13',
                'category'     => '創作, レビュー',
                'release_flag' => true,
            ],
            [
                'title'        => '第11回うでぃおふふつかめに参加しました',
                'date'         => '2019/03/24',
                'category'     => 'レポート',
                'release_flag' => true,
            ],
            [
                'title'        => '新生活と作ったものについて',
                'date'         => '2019/04/01',
                'category'     => '独り言, 創作, ゲーム制作',
                'release_flag' => true,
            ],
            [
                'title'        => 'ラズパイはじめました',
                'date'         => '2019/05/06',
                'category'     => '電子工作',
                'release_flag' => true,
            ],
            [
                'title'        => 'ラズパイで室温を測ってみました',
                'date'         => '2019/05/12',
                'category'     => '電子工作',
                'release_flag' => true,
            ],
            [
                'title'        => '3,000円のスマートウォッチを買いました',
                'date'         => '2019/05/21',
                'category'     => 'レビュー',
                'release_flag' => true,
            ],
            [
                'title'        => '高尾山に登ってきました',
                'date'         => '2019/06/09',
                'category'     => '山歩',
                'release_flag' => true,
            ],
            [
                'title'        => '秋の生存報告',
                'date'         => '2019/09/24',
                'category'     => '独り言',
                'release_flag' => true,
            ],
            [
                'title'        => 'ガルパン 4D で 4DX と MX4D の違いを体験してきました',
                'date'         => '2019/11/15',
                'category'     => 'レビュー',
                'release_flag' => true,
            ],
            [
                'title'        => '220円だったので Echo Dot を買いました',
                'date'         => '2019/10/27',
                'category'     => 'レビュー',
                'release_flag' => true,
            ],
            [
                'title'        => '評価というものについて考えてみました',
                'date'         => '2019/11/15',
                'category'     => '独り言',
                'release_flag' => true,
            ],
            [
                'title'        => '愛宕山に登ってきました',
                'date'         => '2019/11/30',
                'category'     => '山歩',
                'release_flag' => true,
            ],
            [
                'title'        => 'ホームシアターを作りたかったんです',
                'date'         => '2019/12/01',
                'category'     => '独り言, レビュー',
                'release_flag' => true,
            ],
            [
                'title'        => '2020年あけました',
                'date'         => '2020/01/04',
                'category'     => '独り言',
                'release_flag' => true,
            ],
            [
                'title'        => '六個山と箕面山を縦走してきました',
                'date'         => '2020/01/19',
                'category'     => '山歩',
                'release_flag' => true,
            ],
            [
                'title'        => 'laravel でログイン処理を作ってみました',
                'date'         => '2020/02/10',
                'category'     => 'PHP',
                'release_flag' => true,
            ],
            [
                'title'        => '六甲山に登ってきました',
                'date'         => '2020/02/22',
                'category'     => '山歩',
                'release_flag' => true,
            ],
            [
                'title'        => 'ウディフェス始まってました',
                'date'         => '2020/02/26',
                'category'     => '独り言',
                'file'         => 'storage/posts/44',
                'release_flag' => true,
            ],
        ]);
    }
}
