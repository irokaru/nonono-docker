<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Game;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Util\TestTool;
use Tests\TestCase;

class GameApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_game_index_ok()
    {
        $games = factory(Game::class, 3)->create();
        $games_array = static::Collection2Array($games);

        $response = $this->get(route('games.index'));
        $response->assertOk()->assertExactJson($games_array);
    }

    public function test_game_index_ok_not_released()
    {
        $games = factory(Game::class, 3)->create(['release_flag' => false,]);

        $response = $this->get(route('games.index'));
        $response->assertOk()->assertExactJson([]);
    }

    public function test_game_index_all_ok()
    {
        $games_released   = factory(Game::class, 3)->create();
        $games_no_release = factory(Game::class, 3)->create(['release_flag' => false,]);
        $admin            = factory(Admin::class)->create();
        $auth             = TestTool::getJwtAuthorization($admin);

        $games_array = array_merge(static::Collection2Array($games_released), static::Collection2Array($games_no_release));

        $response = $this->withHeaders($auth)->get(route('games.index.all'));
        $response->assertOk()->assertExactJson($games_array);
    }

    public function test_game_index_all_ng_no_auth()
    {
        $response = $this->get(route('games.index.all'));
        $response->assertStatus(401);
    }

    // ==============================================================

    /**
     * コレクションを配列に変換する
     * @param \Illuminate\Database\Eloquent\Collection $games
     * @return array
     */
    protected static function Collection2Array($games)
    {
        $reject_keys = [
            'id', 'release_flag', 'created_at', 'updated_at',
        ];

        $games_array = $games->toArray();
        for ($i = 0; $i < count($games_array); $i++) {
            foreach($reject_keys as $key) {
                unset($games_array[$i][$key]);
            }
        }

        return $games_array;
    }
}
