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

    public function test_history_store_ng_validate()
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

    public function test_history_store_ng_no_auth()
    {
        $history = factory(History::class)->make();
        $history_array = $history->toArray();
        $response = $this->json('POST', route('history.store'), $history_array);
        $response->assertStatus(401);
    }

    public function test_history_update_ok()
    {
        $admin = factory(Admin::class)->create();
        $auth = TestTool::getJwtAuthorization($admin);

        $history = factory(History::class)->create();
        $history_array = $history->toArray();
        $history_array['id'] = 1;
        unset($history_array['created_at']);
        unset($history_array['updated_at']);

        $suites = [
            ['2000-01-01', 'hogehoge'],
            ['2020-02-10', 'fugafuga'],
        ];

        foreach ($suites as $suite) {
            $history_array['date']        = $suite[0];
            $history_array['discription'] = $suite[1];

            $res_update = $this->withHeaders($auth)->json('PUT', route('history.update'), $history_array);
            $res_update->assertOk();

            $res_index = $this->get(route('history.index'));
            $res_index->assertOk()->assertExactJson([$history_array]);
        }
    }

    public function test_history_update_ng_validate()
    {
        $admin = factory(Admin::class)->create();
        $auth = TestTool::getJwtAuthorization($admin);

        $history = factory(History::class)->create();
        $history_array = $history->toArray();
        $history_array['id'] = 1;
        unset($history_array['created_at']);
        unset($history_array['updated_at']);

        $suites = [
         // [date, discription],
            ['2020-01-01', null],
            ['0000', 'hogehoge'],
            [null,   'hogehoge'],
            [null,   null],
        ];

        foreach ($suites as $suite) {
            $history_array['date']        = $suite[0];
            $history_array['discription'] = $suite[1];

            $res_update = $this->withHeaders($auth)->json('PUT', route('history.update'), $history_array);
            $res_update->assertStatus(422);
        }
    }

    public function test_history_update_ng_no_auth()
    {
        $history = factory(History::class)->create();
        $history_array = $history->toArray();
        $history_array['id'] = 1;

        $response = $this->json('PUT', route('history.update'), $history_array);
        $response->assertStatus(401);
    }

    public function test_history_delete_ok()
    {
        $admin = factory(Admin::class)->create();
        $auth = TestTool::getJwtAuthorization($admin);

        $history = factory(History::class, 5)->create();
        $target_id = 3;

        $res_delete = $this->withHeaders($auth)->json('DELETE', route('history.delete', ['id' => $target_id]));
        $res_delete->assertOk();
        $this->assertTrue(History::where('id', $target_id)->doesntExist());
    }

    public function test_history_delete_ng_validate()
    {
        $admin = factory(Admin::class)->create();
        $auth = TestTool::getJwtAuthorization($admin);

        $history = factory(History::class, 5)->create();

        $res_delete = $this->withHeaders($auth)->json('DELETE', route('history.delete', ['id' => -1]));
        $res_delete->assertStatus(422);
    }

    public function test_history_delete_ng_no_auth()
    {
        $history = factory(History::class, 5)->create();

        $res_delete = $this->json('DELETE', route('history.delete', ['id' => 1]));
        $res_delete->assertStatus(401);
    }

    public function test_history_delete_ng_doesnt_exist_data()
    {
        $admin = factory(Admin::class)->create();
        $auth = TestTool::getJwtAuthorization($admin);

        $history = factory(History::class, 5)->create();

        $res_delete = $this->withHeaders($auth)->json('DELETE', route('history.delete', ['id' => 999]));
        $res_delete->assertStatus(400);
    }

}
