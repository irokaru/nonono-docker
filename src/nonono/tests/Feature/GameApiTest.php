<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Game;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

use Tests\Util\GameDummy;
use Tests\Util\TestTool;
use Tests\TestCase;

class GameApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_game_index_ok()
    {
        $games = factory(Game::class, 3)->create();
        $games_array = static::collection2array($games);

        $response = $this->get(route('games.index'));
        $response->assertOk()->assertExactJson($games_array);
    }

    public function test_game_index_ok_not_released()
    {
        factory(Game::class, 3)->create(['release_flag' => false,]);

        $response = $this->get(route('games.index'));
        $response->assertOk()->assertExactJson([]);
    }

    public function test_game_index_all_ok()
    {
        $games_released   = factory(Game::class, 3)->create();
        $games_no_release = factory(Game::class, 3)->create(['release_flag' => false,]);
        $admin            = factory(Admin::class)->create();
        $auth             = TestTool::getJwtAuthorization($admin);

        $games_array = array_merge(static::collection2array($games_released), static::collection2array($games_no_release));

        $response = $this->withHeaders($auth)->get(route('games.index.all'));
        $response->assertOk()->assertExactJson($games_array);
    }

    public function test_game_index_all_ng_no_auth()
    {
        factory(Game::class, 3)->create();
        factory(Game::class, 3)->create(['release_flag' => false,]);

        $response = $this->get(route('games.index.all'));
        $response->assertStatus(401)->assertExactJson(['error' => 'Unauthorized']);
    }

    public function test_game_store_ok()
    {
        $game  = static::makePostDataArray();
        $admin = factory(Admin::class)->create();
        $auth  = TestTool::getJwtAuthorization($admin);

        $response = $this->post(route('games.store'), $game, $auth);
        $response->assertOk();

        $result = Game::all()->first()->toArray();

        $compare = [
            'title', 'release_date', 'release_flag', 'thumbnail_path', 'category', 'infomation', 'url',
        ];

        foreach ($compare as $key) {
            $this->assertEquals($game[$key], $result[$key]);
        }
    }

    public function test_game_store_ng_no_auth()
    {
        $game = static::makePostDataArray();

        $response = $this->post(route('games.store'), $game);
        $response->assertStatus(401)->assertExactJson(['error' => 'Unauthorized']);

        $result = Game::all()->toArray();
        $this->assertEquals([], $result);
    }

    public function test_game_store_ng_validate()
    {
        $game  = static::makePostDataArray();
        $admin = factory(Admin::class)->create();
        $auth  = TestTool::getJwtAuthorization($admin);

        $patterns = GameDummy::getNgPatterns();

        foreach ($patterns as $pattern) {
            $post_data = $game;

            $post_data['title']          = $pattern[0];
            $post_data['release_date']   = $pattern[1];
            $post_data['release_flag']   = $pattern[2];
            $post_data['thumbnail_name'] = $pattern[3];
            $post_data['category']       = $pattern[4];
            $post_data['infomation']     = $pattern[5];
            $post_data['url']            = $pattern[6];

            $response = $this->post(route('games.store'), $post_data, $auth);
            $response->assertStatus(422);
        }

        $result = Game::all()->toArray();
        $this->assertEquals([], $result);
    }

    public function test_game_update_ok()
    {
        $game = static::makePutDataArray();
        $admin = factory(Admin::class)->create();
        $auth  = TestTool::getJwtAuthorization($admin);

        $suites = [
            // key, value
            ['title',          'hogehoge'],
            ['release_date',   '2020-01-01'],
            ['release_flag',   false],
            ['thumbnail_name', 'hogehogehoge'],
            ['category',       'thisiscategory'],
            ['infomation',     'thisisinfomation'],
            ['url',            '/test/test/test'],
        ];

        foreach ($suites as $suite) {
            $put_data = $game;
            $put_data[$suite[0]] = $suite[1];
            if ($suite[0] === 'thumbnail_name') {
                $put_data['thumbnail_path'] = '/img/game/'.$suite[1].'.png';
            } else {
                $put_data['thumbnail_path'] = preg_replace('/jpg$/', 'png', $put_data['thumbnail_path']);
            }

            $response_store = $this->put(route('games.update'), $put_data, $auth);
            $response_store->assertOk();

            $result = Game::find($game['id'])->toArray();
            unset($put_data['thumbnail_name'], $put_data['thumbnail'], $put_data['created_at'], $put_data['updated_at']);
            unset($result['thumbnail_name'], $result['thumbnail'], $result['created_at'], $result['updated_at']);

            $this->assertEquals($put_data, $result);
        }
    }

    public function test_game_update_ng_no_auth()
    {
        $game = static::makePutDataArray();

        $response_store = $this->put(route('games.update'), $game);
        $response_store->assertStatus(401)->assertExactJson(['error' => 'Unauthorized']);

        $result = Game::find($game['id'])->toArray();
        unset($game['thumbnail_name'], $game['thumbnail']);
        $this->assertEquals($result, $game);
    }

    public function test_game_update_ng_validate()
    {
        $game = static::makePutDataArray();
        $admin = factory(Admin::class)->create();
        $auth  = TestTool::getJwtAuthorization($admin);

        $suites = [
            // key, value
            ['title',          ''],
            ['release_date',   'aiueo'],
            ['release_flag',   'ayayay'],
            ['thumbnail_name', ''],
            ['category',       ''],
            ['infomation',     Str::random(300)],
            ['url',            ''],
        ];

        foreach ($suites as $suite) {
            $put_data = $game;
            $put_data[$suite[0]] = $suite[1];
            if ($suite[0] === 'thumbnail_name') {
                $put_data['thumbnail_path'] = '/img/game/'.$suite[1].'.png';
            } else {
                $put_data['thumbnail_path'] = preg_replace('/jpg$/', 'png', $put_data['thumbnail_path']);
            }

            $response_store = $this->put(route('games.update'), $put_data, $auth);
            $response_store->assertStatus(422);

            $result = Game::find($game['id'])->toArray();
            unset($put_data['thumbnail_name'], $put_data['thumbnail'], $put_data['created_at'], $put_data['updated_at']);
            unset($result['thumbnail_name'], $result['thumbnail'], $result['created_at'], $result['updated_at']);

            $this->assertNotEquals($put_data, $result);
        }
    }

    // ==============================================================

    /**
     * コレクションを配列に変換する
     * @param \Illuminate\Database\Eloquent\Collection $games
     * @return array
     */
    protected static function collection2array($games, $reject_keys=[])
    {
        if ($reject_keys === []) {
            $reject_keys = [
                'release_flag', 'created_at', 'updated_at',
            ];
        }

        return TestTool::collection2array($games, $reject_keys);
    }

    /**
     * 投稿用ゲーム配列を生成する
     * @return array
     */
    protected static function makePostDataArray()
    {
        $game = factory(Game::class)->make();
        $array = $game->toArray();
        $array['thumbnail'] = UploadedFile::fake()->image('hoge.png', 100, 100)->size(1000);
        $array['thumbnail_name'] = 'hoge';
        $array['thumbnail_path'] = '/img/game/hoge.png';
        return $array;
    }

    /**
     * 更新用ゲーム配列を生成する
     */
    protected static function makePutDataArray()
    {
        $game = factory(Game::class)->create();
        $array = $game->toArray();
        $array['thumbnail'] = UploadedFile::fake()->image('hoge.png', 100, 100)->size(1000);
        $array['thumbnail_name'] = 'hoge';

        return $array;
    }
}
