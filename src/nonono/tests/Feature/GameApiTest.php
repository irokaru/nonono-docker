<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Game;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
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
        $game = static::makePostDataArray();
        $admin            = factory(Admin::class)->create();
        $auth             = TestTool::getJwtAuthorization($admin);

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

    // ==============================================================

    /**
     * コレクションを配列に変換する
     * @param \Illuminate\Database\Eloquent\Collection $games
     * @return array
     */
    protected static function collection2array($games)
    {
        $reject_keys = [
            'id', 'release_flag', 'created_at', 'updated_at',
        ];

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
}
