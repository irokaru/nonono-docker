<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\History;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Util\TestTool;

class HistoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_history_index_ok()
    {
        $histories = factory(History::class, 5)->create();
        $response  = $this->get(route('history.index'));

        $history_array    = $histories->toArray();
        $expect_histories = [];
        foreach ($history_array as $history) {
            unset($history['created_at']);
            unset($history['updated_at']);

            array_push($expect_histories, $history);
        }

        $response->assertOk()->assertExactJson($expect_histories);
    }

    public function test_history_store_ok()
    {
        $admin = factory(Admin::class)->create();
        $auth = TestTool::getJwtAuthorization($admin);

        $history = factory(History::class)->make();
        $history_array = $history->toArray();
        $history_array['id'] = 1;

        $res_store = $this->withHeaders($auth)->json('POST', route('history.store'), $history_array);
        $res_store->assertOk();

        $res_index = $this->get(route('history.index'));
        $res_index->assertOk()->assertExactJson([$history_array]);
    }

    public function test_history_ng_validate()
    {
        $admin = factory(Admin::class)->create();
        $auth = TestTool::getJwtAuthorization($admin);

        $suites = [
         // [date, discription],
            ['2020-01-01', null],
            ['0000', 'hogehoge'],
            [null,   'hogehoge'],
            [null,   null],
        ];

        foreach ($suites as $suite) {
            $response = $this->withHeaders($auth)->json('POST', route('history.store'), [
                'date'        => $suite[0],
                'discription' => $suite[1],
            ]);
            $response->assertStatus(422);
        }
    }

    public function test_history_ng_no_auth()
    {
        $history = factory(History::class)->make();
        $history_array = $history->toArray();
        $response = $this->json('POST', route('history.store'), $history_array);
        $response->assertStatus(401);
    }
}
